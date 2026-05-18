<?php

namespace App\Filament\Pages;

use App\Models\ClusteringSession;
use App\Models\Warga;
use Filament\Pages\Page;

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -2;
    protected static string $view = 'filament.pages.custom-dashboard';
    protected static ?string $slug = 'dashboard';

    public int $totalWarga = 0;
    public ?ClusteringSession $latestSession = null;
    public array $clusterDistribution = [];
    public array $avgIncomePerCluster = [];
    public array $recentWarga = [];
    public int $totalSessions = 0;

    public function mount(): void
    {
        $this->totalWarga = Warga::count();
        $this->totalSessions = ClusteringSession::count();
        $this->latestSession = ClusteringSession::with(['results', 'centroids'])->latest()->first();

        if ($this->latestSession) {
            // Cluster distribution
            $distribution = $this->latestSession->results()
                ->selectRaw('label, COUNT(*) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $this->clusterDistribution = [
                'Rendah' => $distribution['Rendah'] ?? 0,
                'Sedang' => $distribution['Sedang'] ?? 0,
                'Tinggi' => $distribution['Tinggi'] ?? 0,
            ];

            // Average income per cluster
            foreach (['Rendah', 'Sedang', 'Tinggi'] as $label) {
                $ids = $this->latestSession->results->where('label', $label)->pluck('warga_id');
                $this->avgIncomePerCluster[$label] = $ids->isNotEmpty()
                    ? round(Warga::whereIn('id', $ids)->avg('pendapatan'), 0)
                    : 0;
            }
        }

        // Recent warga (latest 5)
        $this->recentWarga = Warga::with(['pekerjaan', 'latestClusteringResult'])
            ->latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    public static function canAccess(): bool
    {
        return true;
    }
}
