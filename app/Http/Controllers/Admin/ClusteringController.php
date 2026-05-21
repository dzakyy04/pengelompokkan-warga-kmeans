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
        $latestSession = ClusteringSession::with('centroids')->latest()->first();
        return view('admin.clustering.index', compact('totalWarga', 'latestSession'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'jumlah_cluster' => 'required|integer|min:2|max:5',
            'max_iterasi' => 'required|integer|min:10|max:500',
        ]);

        try {
            $service = new KMeansService();
            $session = $service->process($request->jumlah_cluster, $request->max_iterasi);
            return redirect()->route('admin.clustering.show', $session->id)->with('success', 'Proses clustering berhasil! Session #' . $session->id);
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
        $session = ClusteringSession::with(['centroids', 'user', 'validatedByUser', 'results.warga.pendidikan', 'results.warga.kondisiRumah', 'results.warga.bansos'])->findOrFail($id);
        return view('admin.clustering.show', compact('session'));
    }

    public function validateSession(Request $request, $id)
    {
        $session = ClusteringSession::findOrFail($id);
        if ($session->status !== 'completed') {
            return back()->with('error', 'Session ini tidak bisa divalidasi.');
        }
        $session->update([
            'status' => 'validated',
            'catatan_validasi' => $request->catatan_validasi,
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);
        return back()->with('success', 'Hasil clustering telah divalidasi.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['catatan_validasi' => 'required']);
        $session = ClusteringSession::findOrFail($id);
        if ($session->status !== 'completed') {
            return back()->with('error', 'Session ini tidak bisa ditolak.');
        }
        $session->update([
            'status' => 'rejected',
            'catatan_validasi' => $request->catatan_validasi,
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);
        return back()->with('success', 'Hasil clustering telah ditolak.');
    }

    public function downloadPdf($id)
    {
        $session = ClusteringSession::with(['results.warga.pendidikan', 'results.warga.kondisiRumah', 'results.warga.bansos', 'centroids', 'user', 'validatedByUser'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-clustering', ['session' => $session])
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20)
            ->setOption('margin-left', 20)
            ->setOption('margin-right', 20);
        return response()->streamDownload(fn() => print($pdf->output()), "laporan-clustering-{$session->id}.pdf");
    }

    public function activateBaseModel($id)
    {
        $session = ClusteringSession::findOrFail($id);
        try {
            $service = new KMeansService();
            $service->activateAsBaseModel($session);
            return back()->with('success', 'Session #' . $session->id . ' berhasil dijadikan Base Model aktif.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pendingClassifications()
    {
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

        return back()->with('success', 'Klasifikasi warga berhasil disetujui.');
    }

    public function rejectClassification(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $item = WargaClassificationQueue::findOrFail($id);
        $updateData = [
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ];

        if ($request->filled('revised_cluster')) {
            $centroid = ClusterCentroid::where('session_id', $item->base_model_session_id)
                ->where('cluster', $request->revised_cluster)->first();
            if ($centroid) {
                $updateData['revised_cluster'] = $request->revised_cluster;
                $updateData['revised_label'] = $centroid->label;
                $updateData['status'] = 'approved';

                ClusteringResult::create([
                    'session_id' => $item->base_model_session_id,
                    'warga_id' => $item->warga_id,
                    'cluster' => $request->revised_cluster,
                    'label' => $centroid->label,
                    'jarak_ke_centroid' => $item->distance_to_centroid,
                ]);
            }
        }

        $item->update($updateData);

        return back()->with('success', 'Klasifikasi warga telah ditinjau.');
    }
}
