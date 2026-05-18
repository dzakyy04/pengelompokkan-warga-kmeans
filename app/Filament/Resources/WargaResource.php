<?php
namespace App\Filament\Resources;
use App\Filament\Resources\WargaResource\Pages;
use App\Models\Warga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WargaResource extends Resource
{
    protected static ?string $model = Warga::class;
    protected static ?string $slug = 'data-warga';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Data Warga';
    protected static ?string $navigationLabel = 'Daftar Warga';
    protected static ?string $modelLabel = 'Warga';
    protected static ?string $pluralModelLabel = 'Data Warga';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Pribadi')->schema([
                Forms\Components\TextInput::make('nama_lengkap')->label('Nama Lengkap')->required()->maxLength(255),
                Forms\Components\TextInput::make('nik')->label('NIK')->required()->maxLength(16)->minLength(16)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('rt_rw')->label('RT / RW')->placeholder('RT 01 / RW 02'),
            ])->columns(3),
            Forms\Components\Section::make('Data Ekonomi')->schema([
                Forms\Components\Select::make('pekerjaan_id')->label('Pekerjaan')->relationship('pekerjaan', 'nama')->required()->searchable()->preload(),
                Forms\Components\TextInput::make('pendapatan')->label('Pendapatan per Bulan')->required()->mask(\Filament\Support\RawJs::make('$money($input, \',\', \'.\', 0)'))->stripCharacters('.')->prefix('Rp'),
                Forms\Components\TextInput::make('jumlah_tanggungan')->label('Jumlah Tanggungan')->required()->numeric()->minValue(0)->maxValue(20),
            ])->columns(3),
            Forms\Components\Section::make('Kondisi Rumah & Aset')->schema([
                Forms\Components\Select::make('kondisi_rumah_id')->label('Kondisi Rumah')->relationship('kondisiRumah', 'nama')->required()->searchable()->preload(),
                Forms\Components\CheckboxList::make('asets')->label('Aset yang Dimiliki')->relationship('asets', 'nama')->columns(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nik')->label('NIK')->searchable()->sortable()->copyable(),
            Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('pekerjaan.nama')->label('Pekerjaan')->sortable()->toggleable(),
            Tables\Columns\TextColumn::make('pendapatan')->label('Pendapatan')->money('IDR', locale: 'id')->sortable(),
            Tables\Columns\TextColumn::make('jumlah_tanggungan')->label('Tanggungan')->sortable()->alignCenter(),
            Tables\Columns\TextColumn::make('kondisiRumah.nama')->label('Kondisi Rumah')->sortable()->toggleable(),
            Tables\Columns\TextColumn::make('asets.nama')->label('Aset')->separator(', ')->toggleable(),
            Tables\Columns\TextColumn::make('latestClusteringResult.label')
                ->label('Cluster Terakhir')
                ->badge()
                ->color(fn (?string $state) => match ($state) {
                    'Rendah' => 'danger',
                    'Sedang' => 'warning',
                    'Tinggi' => 'success',
                    default => 'gray',
                })
                ->sortable()
                ->placeholder('Belum di-cluster'),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('pekerjaan_id')->label('Pekerjaan')->relationship('pekerjaan', 'nama'),
            Tables\Filters\SelectFilter::make('kondisi_rumah_id')->label('Kondisi Rumah')->relationship('kondisiRumah', 'nama'),
        ])
        ->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
        ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
        ->defaultSort('nama_lengkap');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListWargas::route('/'), 'create' => Pages\CreateWarga::route('/create'), 'edit' => Pages\EditWarga::route('/{record}/edit')];
    }
}
