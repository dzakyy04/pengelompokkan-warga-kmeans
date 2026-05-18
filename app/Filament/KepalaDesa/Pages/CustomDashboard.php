<?php

namespace App\Filament\KepalaDesa\Pages;

use App\Models\ClusteringSession;
use App\Models\Warga;
use Filament\Pages\Page;

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -2;
    protected static string $view = 'filament.kepala-desa.pages.custom-dashboard';
    protected static ?string $slug = 'dashboard';

    public int $totalWarga = 0;
    public int $menungguValidasi = 0;
    public int $totalValidasi = 0;
    public ?ClusteringSession $latestValidated = null;
    public array $clusterDistribution = [];
    public array $avgIncomePerCluster = [];

    public function mount(): void
    {
        $this->totalWarga = Warga::count();
        $this->menungguValidasi = ClusteringSession::where('status', 'completed')->count();
        $this->totalValidasi = ClusteringSession::where('status', 'validated')->count();
        $this->latestValidated = ClusteringSession::with(['results', 'centroids'])
            ->where('status', 'validated')
            ->latest()
            ->first();

        $session = $this->latestValidated ?? ClusteringSession::with(['results', 'centroids'])->latest()->first();

        if ($session) {
            $distribution = $session->results()
                ->selectRaw('label, COUNT(*) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $this->clusterDistribution = [
                'Rendah' => $distribution['Rendah'] ?? 0,
                'Sedang' => $distribution['Sedang'] ?? 0,
                'Tinggi' => $distribution['Tinggi'] ?? 0,
            ];

            foreach (['Rendah', 'Sedang', 'Tinggi'] as $label) {
                $ids = $session->results->where('label', $label)->pluck('warga_id');
                $this->avgIncomePerCluster[$label] = $ids->isNotEmpty()
                    ? round(Warga::whereIn('id', $ids)->avg('pendapatan'), 0)
                    : 0;
            }
        }
    }

    public static function canAccess(): bool
    {
        return true;
    }
}
