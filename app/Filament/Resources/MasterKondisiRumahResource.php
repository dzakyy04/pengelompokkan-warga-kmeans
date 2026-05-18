<?php
namespace App\Filament\Resources;
use App\Filament\Resources\MasterKondisiRumahResource\Pages;
use App\Models\MasterKondisiRumah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MasterKondisiRumahResource extends Resource
{
    protected static ?string $model = MasterKondisiRumah::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Kondisi Rumah';
    protected static ?string $modelLabel = 'Kondisi Rumah';
    protected static ?string $pluralModelLabel = 'Data Kondisi Rumah';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')->label('Nama Kondisi Rumah')->required()->maxLength(255),
            Forms\Components\TextInput::make('skor')->label('Skor (1-3)')->required()->numeric()->minValue(1)->maxValue(3)
                ->helperText('1 = Paling Rendah (Menumpang), 3 = Paling Tinggi (Milik Sendiri)'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->label('No')->sortable(),
            Tables\Columns\TextColumn::make('nama')->label('Kondisi Rumah')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('skor')->label('Skor')->sortable()->badge()->color(fn(int $state)=>match(true){$state>=4=>'success',$state>=3=>'warning',default=>'danger'}),
            Tables\Columns\TextColumn::make('wargas_count')->label('Jumlah Warga')->counts('wargas')->sortable(),
        ])
        ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
        ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListMasterKondisiRumah::route('/'), 'create' => Pages\CreateMasterKondisiRumah::route('/create'), 'edit' => Pages\EditMasterKondisiRumah::route('/{record}/edit')];
    }
}
