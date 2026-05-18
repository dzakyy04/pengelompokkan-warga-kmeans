<?php
namespace App\Filament\KepalaDesa\Widgets;
use App\Models\ClusteringSession;
use App\Models\Warga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KepalaDesaStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Warga', Warga::count())->icon('heroicon-o-users')->color('primary'),
            Stat::make('Menunggu Validasi', ClusteringSession::where('status', 'completed')->count())->icon('heroicon-o-clock')->color('warning'),
            Stat::make('Total Divalidasi', ClusteringSession::where('status', 'validated')->count())->icon('heroicon-o-check-badge')->color('success'),
        ];
    }
}
