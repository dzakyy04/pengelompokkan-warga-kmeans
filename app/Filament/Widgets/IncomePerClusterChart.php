<?php
namespace App\Filament\Widgets;
use App\Models\ClusteringSession;
use App\Models\Warga;
use Filament\Widgets\ChartWidget;

class IncomePerClusterChart extends ChartWidget
{
    protected static ?string $heading = 'Rata-rata Pendapatan per Cluster';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $session = ClusteringSession::with('results')->latest()->first();
        if (!$session) return ['datasets' => [['data' => [0, 0, 0], 'backgroundColor' => ['#ef4444', '#f59e0b', '#22c55e']]], 'labels' => ['Rendah', 'Sedang', 'Tinggi']];
        $avg = [];
        foreach (['Rendah', 'Sedang', 'Tinggi'] as $label) {
            $ids = $session->results->where('label', $label)->pluck('warga_id');
            $avg[$label] = $ids->isNotEmpty() ? round(Warga::whereIn('id', $ids)->avg('pendapatan'), 0) : 0;
        }
        return ['datasets' => [['label' => 'Rata-rata Pendapatan (Rp)', 'data' => array_values($avg), 'backgroundColor' => ['#ef4444', '#f59e0b', '#22c55e']]], 'labels' => ['Rendah', 'Sedang', 'Tinggi']];
    }

    protected function getType(): string { return 'bar'; }
}
