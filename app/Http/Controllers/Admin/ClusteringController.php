<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClusteringSession;
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
        $sessions = ClusteringSession::with('user')->withCount('results')->latest()->paginate(10);
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
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-clustering', ['session' => $session]);
        return response()->streamDownload(fn() => print($pdf->output()), "laporan-clustering-{$session->id}.pdf");
    }
}
