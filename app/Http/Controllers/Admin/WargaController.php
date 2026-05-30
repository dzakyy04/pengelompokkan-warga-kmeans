<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\ClusteringSession;
use App\Models\MasterPendidikan;
use App\Models\MasterKondisiRumah;
use App\Models\MasterBansos;
use App\Services\KMeansService;
use App\Exports\WargaExport;
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
        $presetPekerjaan = \App\Models\Warga::presetPekerjaan();
        $statusProduktivitas = \App\Models\Warga::statusProduktivitas();

        return view('admin.warga.index', compact('wargas', 'pendidikans', 'kondisiRumahs', 'bansos', 'presetPekerjaan', 'statusProduktivitas'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('skor', 'desc')->get();
        $presetPekerjaan = \App\Models\Warga::presetPekerjaan();
        $statusProduktivitas = \App\Models\Warga::statusProduktivitas();
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
                    'Rendah' => 'Ekonomi Rendah',
                    'Sedang' => 'Ekonomi Menengah',
                    'Tinggi' => 'Ekonomi Mampu',
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
        $presetPekerjaan = \App\Models\Warga::presetPekerjaan();
        $statusProduktivitas = \App\Models\Warga::statusProduktivitas();
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

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendidikan_id', 'pekerjaan', 'status_produktivitas', 'skor_produktivitas', 'jumlah_tanggungan', 'kondisi_rumah_id', 'bansos_id');

        $warga->update($data);

        // Re-classify jika base model tersedia
        $classificationMsg = '';
        $baseModel = ClusteringSession::getActiveBaseModel();
        if ($baseModel) {
            try {
                // Hapus klasifikasi pending sebelumnya
                $warga->classificationQueue()
                    ->where('status', 'pending')
                    ->delete();

                $service = new KMeansService();
                $result = $service->classifyNewWarga($warga);
                $labelFriendly = match($result->assigned_label) {
                    'Rendah' => 'Ekonomi Rendah',
                    'Sedang' => 'Ekonomi Menengah',
                    'Tinggi' => 'Ekonomi Mampu',
                    default => $result->assigned_label,
                };
                $classificationMsg = " Kelompok diperbarui ke: {$labelFriendly} (menunggu validasi Kepala Desa).";
            } catch (\Exception $e) {
                $classificationMsg = '';
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
                'Rendah' => 1,
                'Sedang' => 2,
                'Tinggi' => 3,
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
            ->setPaper('a4', 'landscape')
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

        return Excel::download(
            new WargaExport($wargas),
            'data-warga-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
