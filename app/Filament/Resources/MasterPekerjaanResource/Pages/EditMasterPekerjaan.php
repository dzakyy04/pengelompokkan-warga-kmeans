<?php
namespace App\Filament\Resources\MasterPekerjaanResource\Pages;
use App\Filament\Resources\MasterPekerjaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditMasterPekerjaan extends EditRecord { protected static string $resource = MasterPekerjaanResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }
