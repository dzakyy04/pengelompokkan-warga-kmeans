<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClusteringSession;
use App\Models\WargaClassificationQueue;
use App\Models\ClusterCentroid;
use App\Models\ClusteringResult;
use App\Models\Warga;
use App\Services\KMeansService;
use Illuminate\Http\Request;

class ClusteringController extends Controller
{
    public function index()
    {
        $totalWarga = Warga::count();
        $activeSession = ClusteringSession::getActiveBaseModel();
        $latestSession = ClusteringSession::with('centroids')->latest()->first();
        return view('admin.clustering.index', compact('totalWarga', 'activeSession', 'latestSession'));
    }

    public function process(Request $request)
    {
        try {
            $service = new KMeansService();
            $session = $service->process(3, 100);
            return redirect()->route('admin.clustering.show', $session->id)->with('success', 'Pengelompokan warga berhasil! Acuan pengelompokan sudah aktif.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $sessions = ClusteringSession::with('user')->withCount('results')->latest()->get();
        return view('admin.clustering.history', compact('sessions'));
    }

    public function show($id)
    {
        $session = ClusteringSession::with(['centroids', 'user', 'results.warga.pendidikan', 'results.warga.kondisiRumah', 'results.warga.bansos'])->findOrFail($id);
        return view('admin.clustering.show', compact('session'));
    }

    public function downloadPdf($id)
    {
        $session = ClusteringSession::with(['results.warga.pendidikan', 'results.warga.kondisiRumah', 'results.warga.bansos', 'centroids', 'user'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-clustering', ['session' => $session])
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20)
            ->setOption('margin-left', 20)
            ->setOption('margin-right', 20);
        return response()->streamDownload(fn() => print($pdf->output()), "laporan-pengelompokan-{$session->id}.pdf");
    }

    public function downloadExcel($id)
    {
        $session = ClusteringSession::with(['results.warga.pendidikan', 'results.warga.kondisiRumah', 'results.warga.bansos'])->findOrFail($id);
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ClusteringExport($session), "laporan-pengelompokan-{$session->id}.xlsx");
    }

    public function pendingClassifications()
    {
        abort_unless(auth()->user()->isKepalaDesa(), 403, 'Akses ditolak. Hanya Kepala Desa yang dapat mengakses halaman ini.');

        $pending = WargaClassificationQueue::with(['warga.pendidikan', 'warga.kondisiRumah', 'warga.bansos', 'baseModelSession'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $totalPending = WargaClassificationQueue::where('status', 'pending')->count();
        $totalApproved = WargaClassificationQueue::where('status', 'approved')->count();
        $totalRejected = WargaClassificationQueue::where('status', 'rejected')->count();
        $baseModel = ClusteringSession::getActiveBaseModel();

        return view('admin.clustering.pending-classifications', compact('pending', 'totalPending', 'totalApproved', 'totalRejected', 'baseModel'));
    }

    public function approveClassification($id)
    {
        abort_unless(auth()->user()->isKepalaDesa(), 403, 'Akses ditolak. Hanya Kepala Desa yang dapat mengakses halaman ini.');

        $item = WargaClassificationQueue::findOrFail($id);
        $item->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        ClusteringResult::create([
            'session_id' => $item->base_model_session_id,
            'warga_id' => $item->warga_id,
            'cluster' => $item->assigned_cluster,
            'label' => $item->assigned_label,
            'jarak_ke_centroid' => $item->distance_to_centroid,
        ]);

        return back()->with('success', 'Kelompok warga berhasil disetujui.');
    }

    public function rejectClassification(Request $request, $id)
    {
        abort_unless(auth()->user()->isKepalaDesa(), 403, 'Akses ditolak. Hanya Kepala Desa yang dapat mengakses halaman ini.');

        $request->validate([
            'revised_cluster' => 'required'
        ]);

        $item = WargaClassificationQueue::findOrFail($id);
        $centroid = ClusterCentroid::where('session_id', $item->base_model_session_id)
            ->where('cluster', $request->revised_cluster)->first();

        if ($centroid) {
            $item->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'revised_cluster' => $request->revised_cluster,
                'revised_label' => $centroid->label,
            ]);

            ClusteringResult::create([
                'session_id' => $item->base_model_session_id,
                'warga_id' => $item->warga_id,
                'cluster' => $request->revised_cluster,
                'label' => $centroid->label,
                'jarak_ke_centroid' => $item->distance_to_centroid,
            ]);
        }

        return back()->with('success', 'Data warga telah direvisi dan disetujui.');
    }
}
