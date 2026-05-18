<?php
namespace App\Filament\Widgets;
use App\Models\ClusteringSession;
use App\Models\Warga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalWarga = Warga::count();
        $latestSession = ClusteringSession::latest()->first();
        $stats = [Stat::make('Total Warga', $totalWarga)->icon('heroicon-o-users')->color('primary')->description('Data warga terdaftar')];

        if ($latestSession) {
            $r = $latestSession->results()->selectRaw('label, COUNT(*) as total')->groupBy('label')->pluck('total', 'label');
            $stats[] = Stat::make('Cluster Rendah', $r['Rendah'] ?? 0)->icon('heroicon-o-arrow-trending-down')->color('danger')->description('Bantuan bahan pokok');
            $stats[] = Stat::make('Cluster Sedang', $r['Sedang'] ?? 0)->icon('heroicon-o-minus')->color('warning')->description('Pelatihan UMKM');
            $stats[] = Stat::make('Cluster Tinggi', $r['Tinggi'] ?? 0)->icon('heroicon-o-arrow-trending-up')->color('success')->description('Mentor masyarakat');
        }
        return $stats;
    }
}
