<?php
namespace App\Filament\Resources;
use App\Filament\Resources\MasterAsetResource\Pages;
use App\Models\MasterAset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MasterAsetResource extends Resource
{
    protected static ?string $model = MasterAset::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Aset';
    protected static ?string $modelLabel = 'Aset';
    protected static ?string $pluralModelLabel = 'Data Aset';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')->label('Nama Aset')->required()->maxLength(255),
            Forms\Components\TextInput::make('estimasi_nilai')->label('Estimasi Nilai Pasar (Rp)')->required()->mask(\Filament\Support\RawJs::make('$money($input, \',\', \'.\', 0)'))->stripCharacters('.')->prefix('Rp'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->label('No')->sortable(),
            Tables\Columns\TextColumn::make('nama')->label('Nama Aset')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('estimasi_nilai')->label('Estimasi Nilai')->money('IDR', locale: 'id')->sortable(),
            Tables\Columns\TextColumn::make('wargas_count')->label('Jumlah Warga')->counts('wargas')->sortable(),
        ])
        ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
        ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListMasterAset::route('/'), 'create' => Pages\CreateMasterAset::route('/create'), 'edit' => Pages\EditMasterAset::route('/{record}/edit')];
    }
}
