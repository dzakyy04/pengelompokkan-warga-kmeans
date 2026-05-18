<?php
namespace App\Filament\KepalaDesa\Resources;
use App\Filament\KepalaDesa\Resources\ClusteringSessionResource\Pages;
use App\Models\ClusteringSession;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClusteringSessionResource extends Resource
{
    protected static ?string $model = ClusteringSession::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'Hasil & Validasi';
    protected static ?string $modelLabel = 'Hasil Clustering';
    protected static ?string $pluralModelLabel = 'Hasil Clustering';

    public static function canCreate(): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
            Tables\Columns\TextColumn::make('user.name')->label('Diproses Oleh'),
            Tables\Columns\TextColumn::make('results_count')->label('Total Warga')->counts('results')->alignCenter(),
            Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                ->color(fn(string $state)=>match($state){'pending'=>'warning','completed'=>'info','validated'=>'success','rejected'=>'danger',default=>'gray'})
                ->formatStateUsing(fn(string $state)=>ucfirst($state)),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i')->sortable(),
        ])->defaultSort('created_at', 'desc')
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\Action::make('validate')->label('Validasi')->icon('heroicon-o-check-circle')->color('success')
                ->visible(fn(ClusteringSession $r) => $r->status === 'completed')
                ->requiresConfirmation()->modalHeading('Validasi Hasil Clustering')
                ->form([\Filament\Forms\Components\Textarea::make('catatan_validasi')->label('Catatan (Opsional)')->rows(3)])
                ->action(function (ClusteringSession $record, array $data) {
                    $record->update(['status' => 'validated', 'catatan_validasi' => $data['catatan_validasi'] ?? null, 'validated_by' => auth()->id(), 'validated_at' => now()]);
                    \Filament\Notifications\Notification::make()->title('Hasil clustering telah divalidasi')->success()->send();
                }),
            Tables\Actions\Action::make('reject')->label('Tolak')->icon('heroicon-o-x-circle')->color('danger')
                ->visible(fn(ClusteringSession $r) => $r->status === 'completed')
                ->requiresConfirmation()->modalHeading('Tolak Hasil Clustering')
                ->form([\Filament\Forms\Components\Textarea::make('catatan_validasi')->label('Alasan Penolakan')->required()->rows(3)])
                ->action(function (ClusteringSession $record, array $data) {
                    $record->update(['status' => 'rejected', 'catatan_validasi' => $data['catatan_validasi'], 'validated_by' => auth()->id(), 'validated_at' => now()]);
                    \Filament\Notifications\Notification::make()->title('Hasil clustering telah ditolak')->danger()->send();
                }),
            Tables\Actions\Action::make('download_pdf')->label('Download PDF')->icon('heroicon-o-document-arrow-down')->color('gray')
                ->visible(fn(ClusteringSession $r) => $r->status === 'validated')
                ->action(function (ClusteringSession $record) {
                    $session = $record->load(['results.warga.pekerjaan', 'results.warga.kondisiRumah', 'results.warga.asets', 'centroids', 'user', 'validatedByUser']);
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-clustering', ['session' => $session]);
                    return response()->streamDownload(fn() => print($pdf->output()), "laporan-clustering-{$session->id}.pdf");
                }),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Info Session')->schema([
                Infolists\Components\TextEntry::make('user.name')->label('Diproses Oleh'),
                Infolists\Components\TextEntry::make('status')->label('Status')->badge()
                    ->color(fn(string $state)=>match($state){'pending'=>'warning','completed'=>'info','validated'=>'success','rejected'=>'danger',default=>'gray'}),
                Infolists\Components\TextEntry::make('jumlah_cluster')->label('Jumlah Cluster'),
                Infolists\Components\TextEntry::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
            ])->columns(4),
            Infolists\Components\Section::make('Validasi')->schema([
                Infolists\Components\TextEntry::make('validatedByUser.name')->label('Divalidasi Oleh')->placeholder('-'),
                Infolists\Components\TextEntry::make('validated_at')->label('Tanggal Validasi')->dateTime('d M Y H:i')->placeholder('-'),
                Infolists\Components\TextEntry::make('catatan_validasi')->label('Catatan')->placeholder('-')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\KepalaDesa\Resources\ClusteringSessionResource\RelationManagers\ResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListClusteringSessions::route('/'), 'view' => Pages\ViewClusteringSession::route('/{record}')];
    }
}
