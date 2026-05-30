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
        
        // Session yang menunggu verifikasi kades
        $pendingVerificationSession = ClusteringSession::where('status', 'completed')
            ->whereHas('classificationQueue', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->first();
        $pendingVerificationCount = $pendingVerificationSession 
            ? WargaClassificationQueue::where('base_model_session_id', $pendingVerificationSession->id)->where('status', 'pending')->count() 
            : 0;

        return view('admin.clustering.index', compact('totalWarga', 'activeSession', 'latestSession', 'pendingVerificationSession', 'pendingVerificationCount'));
    }

    public function process(Request $request)
    {
        try {
            // Hapus pending items dari session sebelumnya yang belum diverifikasi
            $oldPendingSessions = ClusteringSession::where('status', 'completed')->pluck('id');
            if ($oldPendingSessions->isNotEmpty()) {
                WargaClassificationQueue::whereIn('base_model_session_id', $oldPendingSessions)
                    ->where('status', 'pending')
                    ->delete();
                // Update status session lama jadi rejected
                ClusteringSession::whereIn('id', $oldPendingSessions)->update(['status' => 'rejected']);
            }

            $service = new KMeansService();
            $session = $service->process(3, 100);
            return redirect()->route('admin.clustering.pending-classifications')
                ->with('success', 'Pengelompokan warga berhasil! Semua data menunggu verifikasi Kepala Desa sebelum diaktifkan sebagai acuan.');
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

        $pending = WargaClassificationQueue::with(['warga.pendidikan', 'warga.kondisiRumah', 'warga.bansos', 'baseModelSession.centroids'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $totalPending = WargaClassificationQueue::where('status', 'pending')->count();
        $totalApproved = WargaClassificationQueue::where('status', 'approved')->count();
        $totalRejected = WargaClassificationQueue::where('status', 'rejected')->count();
        $baseModel = ClusteringSession::getActiveBaseModel();

        // Juga ambil session yang masih menunggu verifikasi (status = completed, belum validated)
        $pendingSession = ClusteringSession::where('status', 'completed')
            ->whereHas('classificationQueue', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->first();

        return view('admin.clustering.pending-classifications', compact('pending', 'totalPending', 'totalApproved', 'totalRejected', 'baseModel', 'pendingSession'));
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

        // Cek apakah semua data di session ini sudah diverifikasi
        $this->checkAndActivateSession($item->base_model_session_id);

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

            // Cek apakah semua data di session ini sudah diverifikasi
            $this->checkAndActivateSession($item->base_model_session_id);
        }

        return back()->with('success', 'Data warga telah direvisi dan disetujui.');
    }

    public function approveAllClassifications(Request $request)
    {
        abort_unless(auth()->user()->isKepalaDesa(), 403, 'Akses ditolak. Hanya Kepala Desa yang dapat mengakses halaman ini.');

        $sessionId = $request->input('session_id');

        $query = WargaClassificationQueue::where('status', 'pending');
        if ($sessionId) {
            $query->where('base_model_session_id', $sessionId);
        }

        $pendingItems = $query->get();

        if ($pendingItems->isEmpty()) {
            return back()->with('error', 'Tidak ada data yang menunggu verifikasi.');
        }

        foreach ($pendingItems as $item) {
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
        }

        // Aktivasi session jika semua sudah diverifikasi
        $sessionIds = $pendingItems->pluck('base_model_session_id')->unique();
        foreach ($sessionIds as $sid) {
            $this->checkAndActivateSession($sid);
        }

        return back()->with('success', "Berhasil menyetujui {$pendingItems->count()} data warga sekaligus.");
    }

    /**
     * Cek apakah semua data di session sudah diverifikasi.
     * Jika ya, aktifkan session sebagai base model.
     */
    private function checkAndActivateSession($sessionId)
    {
        $session = ClusteringSession::find($sessionId);
        if (!$session || $session->status === 'validated') {
            return;
        }

        $remainingPending = WargaClassificationQueue::where('base_model_session_id', $sessionId)
            ->where('status', 'pending')
            ->count();

        if ($remainingPending === 0) {
            // Nonaktifkan acuan lama
            ClusteringSession::where('is_base_model', true)->update(['is_base_model' => false]);

            $session->update([
                'status' => 'validated',
                'is_base_model' => true,
                'validated_by' => auth()->id(),
                'validated_at' => now(),
            ]);
        }
    }
}
