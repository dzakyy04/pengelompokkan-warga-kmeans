<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ClusteringSessionResource\Pages;
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
    protected static ?string $navigationGroup = 'Clustering';
    protected static ?string $navigationLabel = 'History Clustering';
    protected static ?string $modelLabel = 'Session Clustering';
    protected static ?string $pluralModelLabel = 'History Clustering';
    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
            Tables\Columns\TextColumn::make('user.name')->label('Diproses Oleh')->sortable(),
            Tables\Columns\TextColumn::make('jumlah_cluster')->label('Cluster')->alignCenter(),
            Tables\Columns\TextColumn::make('results_count')->label('Total Warga')->counts('results')->alignCenter(),
            Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                ->color(fn(string $state)=>match($state){'pending'=>'warning','completed'=>'info','validated'=>'success','rejected'=>'danger',default=>'gray'})
                ->formatStateUsing(fn(string $state)=>ucfirst($state)),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i')->sortable(),
        ])->defaultSort('created_at', 'desc')
        ->actions([Tables\Actions\ViewAction::make()]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Info Session')->schema([
                Infolists\Components\TextEntry::make('user.name')->label('Diproses Oleh'),
                Infolists\Components\TextEntry::make('status')->label('Status')->badge()
                    ->color(fn(string $state)=>match($state){'pending'=>'warning','completed'=>'info','validated'=>'success','rejected'=>'danger',default=>'gray'}),
                Infolists\Components\TextEntry::make('jumlah_cluster')->label('Jumlah Cluster'),
                Infolists\Components\TextEntry::make('created_at')->label('Tanggal Proses')->dateTime('d M Y H:i'),
            ])->columns(4),
            Infolists\Components\Section::make('Target Encoding (Pekerjaan)')->schema([
                Infolists\Components\KeyValueEntry::make('target_encoding_map')->label('Rata-rata Pendapatan per Pekerjaan'),
            ]),
            Infolists\Components\Section::make('Validasi')->schema([
                Infolists\Components\TextEntry::make('validatedByUser.name')->label('Divalidasi Oleh')->placeholder('Belum divalidasi'),
                Infolists\Components\TextEntry::make('validated_at')->label('Tanggal Validasi')->dateTime('d M Y H:i')->placeholder('-'),
                Infolists\Components\TextEntry::make('catatan_validasi')->label('Catatan')->placeholder('-')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\ClusteringSessionResource\RelationManagers\ResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListClusteringSessions::route('/'), 'view' => Pages\ViewClusteringSession::route('/{record}')];
    }
}
