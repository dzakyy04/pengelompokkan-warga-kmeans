<?php
namespace App\Filament\Resources\MasterAsetResource\Pages;
use App\Filament\Resources\MasterAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditMasterAset extends EditRecord { protected static string $resource = MasterAsetResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }
