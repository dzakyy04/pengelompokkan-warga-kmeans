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

        // Distribusi status produktivitas per cluster
        $statusList = ['Stabil', 'Cukup Stabil', 'Tidak Stabil', 'Tidak Produktif'];
        $statusPerCluster = [
            'Rendah' => array_fill_keys($statusList, 0),
            'Sedang' => array_fill_keys($statusList, 0),
            'Tinggi' => array_fill_keys($statusList, 0),
        ];

        if ($latestSession) {
            foreach ($latestSession->results()->get()->groupBy('label') as $label => $results) {
                $clusterDistribution[$label] = $results->count();
                $wargaIds = $results->pluck('warga_id');

                // Hitung distribusi status produktivitas untuk cluster ini
                $dist = Warga::whereIn('id', $wargaIds)
                    ->whereNotNull('status_produktivitas')
                    ->selectRaw('status_produktivitas, count(*) as total')
                    ->groupBy('status_produktivitas')
                    ->pluck('total', 'status_produktivitas');

                foreach ($statusList as $s) {
                    $statusPerCluster[$label][$s] = $dist[$s] ?? 0;
                }
            }
        }

        return view('admin.dashboard', compact(
            'totalWarga', 'latestSession', 'totalSessions',
            'clusterDistribution', 'statusPerCluster', 'statusList'
        ));
    }
}
