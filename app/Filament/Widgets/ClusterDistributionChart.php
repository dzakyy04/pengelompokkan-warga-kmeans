<?php
namespace App\Filament\Widgets;
use App\Models\ClusteringSession;
use Filament\Widgets\ChartWidget;

class ClusterDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Cluster';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $session = ClusteringSession::latest()->first();
        if (!$session) return ['datasets' => [['data' => [0, 0, 0], 'backgroundColor' => ['#ef4444', '#f59e0b', '#22c55e']]], 'labels' => ['Rendah', 'Sedang', 'Tinggi']];
        $r = $session->results()->selectRaw('label, COUNT(*) as total')->groupBy('label')->pluck('total', 'label');
        return ['datasets' => [['data' => [$r['Rendah'] ?? 0, $r['Sedang'] ?? 0, $r['Tinggi'] ?? 0], 'backgroundColor' => ['#ef4444', '#f59e0b', '#22c55e']]], 'labels' => ['Rendah', 'Sedang', 'Tinggi']];
    }

    protected function getType(): string { return 'pie'; }
}
