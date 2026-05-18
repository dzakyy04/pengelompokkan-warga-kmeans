<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClusteringSession;
use App\Models\Warga;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWarga = Warga::count();
        $latestSession = ClusteringSession::with('centroids')->latest()->first();
        $totalSessions = ClusteringSession::count();

        $clusterDistribution = ['Rendah' => 0, 'Sedang' => 0, 'Tinggi' => 0];
        $avgIncomePerCluster = ['Rendah' => 0, 'Sedang' => 0, 'Tinggi' => 0];

        if ($latestSession) {
            foreach ($latestSession->results()->get()->groupBy('label') as $label => $results) {
                $clusterDistribution[$label] = $results->count();
                $wargaIds = $results->pluck('warga_id');
                $avgIncomePerCluster[$label] = (int) Warga::whereIn('id', $wargaIds)->avg('pendapatan');
            }
        }

        return view('admin.dashboard', compact(
            'totalWarga', 'latestSession', 'totalSessions',
            'clusterDistribution', 'avgIncomePerCluster'
        ));
    }
}
