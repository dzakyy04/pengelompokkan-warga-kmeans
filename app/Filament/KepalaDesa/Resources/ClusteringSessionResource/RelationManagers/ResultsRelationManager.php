<?php

namespace App\Filament\KepalaDesa\Resources\ClusteringSessionResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';
    protected static ?string $title = 'Hasil Clustering per Warga';
    protected static ?string $recordTitleAttribute = 'label';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('warga.nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('warga.nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warga.pekerjaan.nama')
                    ->label('Pekerjaan')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('warga.pendapatan')
                    ->label('Pendapatan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('warga.jumlah_tanggungan')
                    ->label('Tanggungan')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('warga.kondisiRumah.nama')
                    ->label('Kondisi Rumah')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('warga.asets.nama')
                    ->label('Aset')
                    ->separator(', ')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('label')
                    ->label('Cluster')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Rendah' => 'danger',
                        'Sedang' => 'warning',
                        'Tinggi' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('jarak_ke_centroid')
                    ->label('Jarak ke Centroid')
                    ->numeric(decimalPlaces: 4)
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('label')
            ->filters([
                Tables\Filters\SelectFilter::make('label')
                    ->label('Cluster')
                    ->options([
                        'Rendah' => 'Rendah',
                        'Sedang' => 'Sedang',
                        'Tinggi' => 'Tinggi',
                    ]),
            ])
            ->striped();
    }
}
