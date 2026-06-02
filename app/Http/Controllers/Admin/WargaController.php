<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\ClusteringSession;
use App\Models\MasterPendidikan;
use App\Models\MasterKondisiRumah;
use App\Models\MasterBansos;
use App\Models\MasterPekerjaan;
use App\Services\KMeansService;
use App\Exports\WargaExport;
use App\Imports\WargaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = Warga::with(['pendidikan', 'kondisiRumah', 'bansos', 'latestClusteringResult', 'latestClassification']);

        if ($request->filled('kelompok')) {
            $query->whereHas('latestClusteringResult', function($q) use ($request) {
                $q->where('label', $request->kelompok);
            });
        }
        if ($request->filled('pekerjaan')) {
            $query->where('pekerjaan', $request->pekerjaan);
        }
        if ($request->filled('status_produktivitas')) {
            $query->where('status_produktivitas', $request->status_produktivitas);
        }
        if ($request->filled('tanggungan_min')) {
            $query->where('jumlah_tanggungan', '>=', $request->tanggungan_min);
        }
        if ($request->filled('tanggungan_max')) {
            $query->where('jumlah_tanggungan', '<=', $request->tanggungan_max);
        }
        if ($request->filled('status_validasi')) {
            $query->whereHas('latestClassification', function($q) use ($request) {
                $q->where('status', $request->status_validasi);
            });
        }

        $wargas = $query->latest()->get();

        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('skor', 'desc')->get();
        $presetPekerjaan = MasterPekerjaan::orderBy('skor')->orderBy('nama')->get()->map(fn($p) => ['pekerjaan' => $p->nama, 'status' => $p->status_produktivitas, 'skor' => $p->skor])->toArray();
        $statusProduktivitas = MasterPekerjaan::statusProduktivitasList();

        return view('admin.warga.index', compact('wargas', 'pendidikans', 'kondisiRumahs', 'bansos', 'presetPekerjaan', 'statusProduktivitas'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('skor', 'desc')->get();
        $presetPekerjaan = MasterPekerjaan::orderBy('skor')->orderBy('nama')->get()->map(fn($p) => ['pekerjaan' => $p->nama, 'status' => $p->status_produktivitas, 'skor' => $p->skor])->toArray();
        $statusProduktivitas = MasterPekerjaan::statusProduktivitasList();
        return view('admin.warga.create', compact('pendidikans', 'kondisiRumahs', 'bansos', 'presetPekerjaan', 'statusProduktivitas'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik',
            'pendidikan_id' => 'required|exists:master_pendidikan,id',
            'pekerjaan' => 'required|string|max:255',
            'status_produktivitas' => 'required|string|max:100',
            'skor_produktivitas' => 'required|integer|min:1|max:4',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumah,id',
            'bansos_id' => 'required|exists:master_bansos,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendidikan_id', 'pekerjaan', 'status_produktivitas', 'skor_produktivitas', 'jumlah_tanggungan', 'kondisi_rumah_id', 'bansos_id');

        $warga = Warga::create($data);

        // Auto-classify jika base model tersedia
        $classificationMsg = '';
        $baseModel = ClusteringSession::getActiveBaseModel();
        if ($baseModel) {
            try {
                $service = new KMeansService();
                $result = $service->classifyNewWarga($warga);
                $labelFriendly = match($result->assigned_label) {
                    'Tinggi' => 'Tinggi',
                    'Sedang' => 'Sedang',
                    'Rendah' => 'Rendah',
                    default => $result->assigned_label,
                };
                $classificationMsg = " Otomatis terkelompokkan ke: {$labelFriendly} (menunggu validasi Kepala Desa).";
            } catch (\Exception $e) {
                $classificationMsg = '';
            }
        }

        return redirect()->route('admin.warga.index')
            ->with('success', 'Data warga berhasil ditambahkan.' . $classificationMsg);
    }

    public function edit(Warga $warga)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('skor', 'desc')->get();
        $presetPekerjaan = MasterPekerjaan::orderBy('skor')->orderBy('nama')->get()->map(fn($p) => ['pekerjaan' => $p->nama, 'status' => $p->status_produktivitas, 'skor' => $p->skor])->toArray();
        $statusProduktivitas = MasterPekerjaan::statusProduktivitasList();
        return view('admin.warga.edit', compact('warga', 'pendidikans', 'kondisiRumahs', 'bansos', 'presetPekerjaan', 'statusProduktivitas'));
    }

    public function update(Request $request, Warga $warga)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik,' . $warga->id,
            'pendidikan_id' => 'required|exists:master_pendidikan,id',
            'pekerjaan' => 'required|string|max:255',
            'status_produktivitas' => 'required|string|max:100',
            'skor_produktivitas' => 'required|integer|min:1|max:4',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumah,id',
            'bansos_id' => 'required|exists:master_bansos,id',
        ]);

        // Cek apakah parameter clustering berubah
        $clusteringParams = ['pekerjaan', 'status_produktivitas', 'skor_produktivitas', 'jumlah_tanggungan', 'pendidikan_id', 'kondisi_rumah_id', 'bansos_id'];
        $paramChanged = false;
        foreach ($clusteringParams as $param) {
            if ($warga->{$param} != $request->{$param}) {
                $paramChanged = true;
                break;
            }
        }

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendidikan_id', 'pekerjaan', 'status_produktivitas', 'skor_produktivitas', 'jumlah_tanggungan', 'kondisi_rumah_id', 'bansos_id');
        $warga->update($data);

        // Re-classify hanya jika parameter clustering berubah dan base model tersedia
        $classificationMsg = '';
        if ($paramChanged) {
            $baseModel = ClusteringSession::getActiveBaseModel();
            if ($baseModel) {
                try {
                    // Hapus klasifikasi pending sebelumnya
                    $warga->classificationQueue()
                        ->where('status', 'pending')
                        ->delete();

                    // Hapus hasil clustering lama agar kembali ke status menunggu validasi
                    $warga->clusteringResults()
                        ->where('session_id', $baseModel->id)
                        ->delete();

                    $service = new KMeansService();
                    $result = $service->classifyNewWarga($warga);
                    $classificationMsg = " Prioritas bantuan diperbarui ke: {$result->assigned_label} (menunggu validasi Kepala Desa).";
                } catch (\Exception $e) {
                    $classificationMsg = '';
                }
            }
        }

        return redirect()->route('admin.warga.index')
            ->with('success', 'Data warga berhasil diperbarui.' . $classificationMsg);
    }

    public function destroy(Warga $warga)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $warga->delete();
        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil dihapus.');
    }

    private function getFilteredWarga(Request $request)
    {
        $query = Warga::with(['pendidikan', 'kondisiRumah', 'bansos', 'latestClusteringResult', 'latestClassification']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%"));
        }
        if ($request->filled('kelompok')) {
            $query->whereHas('latestClusteringResult', function($q) use ($request) {
                $q->where('label', $request->kelompok);
            });
        }
        if ($request->filled('pekerjaan')) {
            $query->where('pekerjaan', $request->pekerjaan);
        }
        if ($request->filled('status_produktivitas')) {
            $query->where('status_produktivitas', $request->status_produktivitas);
        }
        if ($request->filled('kondisi_rumah_id')) {
            $query->where('kondisi_rumah_id', $request->kondisi_rumah_id);
        }
        if ($request->filled('bansos_id')) {
            $query->where('bansos_id', $request->bansos_id);
        }
        if ($request->filled('tanggungan')) {
            $query->where('jumlah_tanggungan', $request->tanggungan);
        }

        $wargas = $query->latest()->get();

        return $wargas->sortBy(function($w) {
            $kelompok = '';
            if ($w->latestClusteringResult) {
                $kelompok = $w->latestClusteringResult->label;
            } elseif ($w->latestClassification && $w->latestClassification->status === 'approved') {
                $kelompok = $w->latestClassification->assigned_label;
            } else {
                $kelompok = 'Z';
            }
            
            $order = match($kelompok) {
                'Tinggi' => 1,
                'Sedang' => 2,
                'Rendah' => 3,
                default => 4,
            };
            
            return $order . '_' . strtolower($w->nama_lengkap);
        })->values();
    }

    public function exportPdf(Request $request)
    {
        $wargas = $this->getFilteredWarga($request);
        $search = $request->search;
        $kelompok = $request->kelompok;

        $pdf = Pdf::loadView('pdf.laporan-warga', compact('wargas', 'search', 'kelompok'))
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20)
            ->setOption('margin-left', 20)
            ->setOption('margin-right', 20);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'laporan-data-warga-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function exportExcel(Request $request)
    {
        $wargas = $this->getFilteredWarga($request);

        $filename = 'data-warga-' . now()->format('Y-m-d') . '.xlsx';
        $tempPath = 'temp/' . $filename;

        // Gunakan store() bukan download() untuk menghindari bug ZipStream CRC di PHP 8.2
        Excel::store(new WargaExport($wargas), $tempPath, 'local');

        return response()->download(storage_path('app/' . $tempPath), $filename)->deleteFileAfterSend(true);
    }

    public function importExcel(Request $request)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $import = new WargaImport();
            Excel::import($import, $request->file('file'));

            $imported = $import->getImportedCount();
            $skipped = $import->getSkippedCount();
            $failures = $import->getFailureCount();

            $message = "Berhasil mengimpor {$imported} data warga.";
            if ($skipped > 0) {
                $message .= " {$skipped} data dilewati (NIK duplikat).";
            }
            if ($failures > 0) {
                $message .= " {$failures} baris gagal (data tidak valid/tidak cocok dengan master).";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // ===== Sheet 1: Data Warga =====
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Data Warga');

        $headers = ['nama_lengkap', 'nik', 'rt_rw', 'pekerjaan', 'status_produktivitas', 'jumlah_tanggungan', 'pendidikan', 'kondisi_rumah', 'bansos'];

        // Header styling
        foreach ($headers as $colIndex => $header) {
            $cell = $sheet1->getCellByColumnAndRow($colIndex + 1, 1);
            $cell->setValue($header);
        }
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']],
        ];
        $sheet1->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Contoh data
        $examples = [
            ['Budi Santoso', '1234567890123456', 'RT 01 / RW 02', 'Pedagang', '', 3, 'SMA', 'Milik Sendiri', 'Tidak Menerima'],
            ['Siti Aminah', '6543210987654321', 'RT 03 / RW 01', 'IRT', '', 5, 'SD', 'Sewa', 'PKH atau BLT'],
            ['Ahmad Sopir', '1111222233334444', 'RT 02 / RW 01', 'Sopir', 'Tidak Stabil', 2, 'SMP', 'Milik Sendiri', 'Sembako'],
        ];

        foreach ($examples as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $cell = $sheet1->getCellByColumnAndRow($colIndex + 1, $rowIndex + 2);
                // Kolom NIK (index 1) harus selalu string agar tidak overflow
                if ($colIndex === 1) {
                    $cell->setValueExplicit((string) $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                } else {
                    $cell->setValue($value);
                }
            }
        }

        // Set kolom NIK sebagai text format untuk seluruh kolom
        $sheet1->getStyle('B:B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // ===== Sheet 2: Referensi =====
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Referensi');

        $refHeaders = ['Pendidikan (kolom: pendidikan)', 'Kondisi Rumah (kolom: kondisi_rumah)', 'Bansos (kolom: bansos)', 'Pekerjaan (kolom: pekerjaan)'];
        foreach ($refHeaders as $colIndex => $header) {
            $sheet2->getCellByColumnAndRow($colIndex + 1, 1)->setValue($header);
        }
        $refHeaderStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2563EB']],
        ];
        $lastCol = chr(64 + count($refHeaders)); // D
        $sheet2->getStyle("A1:{$lastCol}1")->applyFromArray($refHeaderStyle);

        // Data referensi
        $pendidikans = MasterPendidikan::orderBy('skor')->pluck('nama')->toArray();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->pluck('nama')->toArray();
        $bansos = MasterBansos::orderBy('skor', 'desc')->pluck('nama')->toArray();
        $pekerjaans = MasterPekerjaan::orderBy('skor')->pluck('nama')->toArray();

        $maxRows = max(count($pendidikans), count($kondisiRumahs), count($bansos), count($pekerjaans));
        for ($i = 0; $i < $maxRows; $i++) {
            $row = $i + 2;
            $sheet2->getCellByColumnAndRow(1, $row)->setValue($pendidikans[$i] ?? '');
            $sheet2->getCellByColumnAndRow(2, $row)->setValue($kondisiRumahs[$i] ?? '');
            $sheet2->getCellByColumnAndRow(3, $row)->setValue($bansos[$i] ?? '');
            $sheet2->getCellByColumnAndRow(4, $row)->setValue($pekerjaans[$i] ?? '');
        }

        // Auto-size kolom
        foreach (range('A', 'I') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A', $lastCol) as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        // Set sheet aktif ke sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        // Simpan ke file temp lalu download
        $tempFile = storage_path('app/temp/template-import-warga.xlsx');
        if (!is_dir(dirname($tempFile))) {
            mkdir(dirname($tempFile), 0755, true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->setPreCalculateFormulas(false);
        $writer->save($tempFile);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return response()->download($tempFile, 'template-import-warga.xlsx')->deleteFileAfterSend(true);
    }
}
