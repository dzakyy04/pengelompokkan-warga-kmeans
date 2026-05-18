<?php
namespace App\Filament\Resources\ClusteringSessionResource\Pages;
use App\Filament\Resources\ClusteringSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewClusteringSession extends ViewRecord
{
    protected static string $resource = ClusteringSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->visible(fn() => $this->record->status === 'validated')
                ->action(function () {
                    $session = $this->record->load(['results.warga.pekerjaan', 'results.warga.kondisiRumah', 'results.warga.asets', 'centroids', 'user', 'validatedByUser']);
                    $pdf = Pdf::loadView('pdf.laporan-clustering', ['session' => $session]);
                    return response()->streamDownload(fn() => print($pdf->output()), "laporan-clustering-{$session->id}.pdf");
                }),
        ];
    }
}
