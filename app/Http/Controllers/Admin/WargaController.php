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

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%"));
        }
        if ($request->filled('kelompok')) {
            $query->whereHas('latestClusteringResult', function($q) use ($request) {
                $q->where('label', $request->kelompok);
            });
        }

        $wargas = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('skor', 'desc')->get();

        return view('admin.warga.index', compact('wargas', 'pendidikans', 'kondisiRumahs', 'bansos'));
    }

    public function create()
    {
        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('skor', 'desc')->get();
        return view('admin.warga.create', compact('pendidikans', 'kondisiRumahs', 'bansos'));
    }

    public function store(Request $request)
    {
        if ($request->has('pendapatan')) {
            $request->merge(['pendapatan' => str_replace('.', '', $request->pendapatan)]);
        }
        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik',
            'pendidikan_id' => 'required|exists:master_pendidikan,id',
            'pendapatan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumah,id',
            'bansos_id' => 'required|exists:master_bansos,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendidikan_id', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id', 'bansos_id');
        $data['pendapatan'] = (int) str_replace('.', '', $data['pendapatan']);

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
        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('skor', 'desc')->get();
        return view('admin.warga.edit', compact('warga', 'pendidikans', 'kondisiRumahs', 'bansos'));
    }

    public function update(Request $request, Warga $warga)
    {
        if ($request->has('pendapatan')) {
            $request->merge(['pendapatan' => str_replace('.', '', $request->pendapatan)]);
        }
        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik,' . $warga->id,
            'pendidikan_id' => 'required|exists:master_pendidikan,id',
            'pendapatan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumah,id',
            'bansos_id' => 'required|exists:master_bansos,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendidikan_id', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id', 'bansos_id');
        $data['pendapatan'] = (int) str_replace('.', '', $data['pendapatan']);

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
        $warga->delete();
        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil dihapus.');
    }

    private function getFilteredWarga(Request $request)
    {
        $query = Warga::with(['pendidikan', 'kondisiRumah', 'bansos', 'latestClusteringResult']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%"));
        }
        if ($request->filled('kelompok')) {
            $query->whereHas('latestClusteringResult', function($q) use ($request) {
                $q->where('label', $request->kelompok);
            });
        }

        return $query->orderBy('nama_lengkap')->get();
    }

    public function exportPdf(Request $request)
    {
        $wargas = $this->getFilteredWarga($request);
        $search = $request->search;
        $kelompok = $request->kelompok;

        $pdf = Pdf::loadView('pdf.laporan-warga', compact('wargas', 'search', 'kelompok'))
            ->setPaper('a4', 'landscape');

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
