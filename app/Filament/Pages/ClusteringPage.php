<?php
namespace App\Filament\Pages;
use App\Models\ClusteringSession;
use App\Models\Warga;
use App\Services\KMeansService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ClusteringPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'Clustering';
    protected static ?string $navigationLabel = 'Proses Clustering';
    protected static ?string $title = 'Proses K-Means Clustering';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.clustering-page';

    public ?ClusteringSession $latestSession = null;
    public int $totalWarga = 0;

    public function mount(): void
    {
        $this->totalWarga = Warga::count();
        $this->latestSession = ClusteringSession::with(['results', 'centroids'])->latest()->first();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('run_clustering')
                ->label('Proses K-Means')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Proses K-Means Clustering')
                ->modalDescription('Apakah Anda yakin ingin memproses clustering? Data warga saat ini akan di-cluster ke dalam 3 kelompok.')
                ->modalSubmitActionLabel('Ya, Proses Sekarang')
                ->disabled(fn() => $this->totalWarga < 3)
                ->action(function () {
                    try {
                        $service = new KMeansService();
                        $session = $service->process(3);
                        $this->latestSession = $session->load(['results', 'centroids']);
                        Notification::make()->title('Clustering Berhasil!')->body("Data {$session->results->count()} warga berhasil dikelompokkan.")->success()->send();
                    } catch (\Exception $e) {
                        Notification::make()->title('Clustering Gagal')->body($e->getMessage())->danger()->send();
                    }
                }),
        ];
    }
}
