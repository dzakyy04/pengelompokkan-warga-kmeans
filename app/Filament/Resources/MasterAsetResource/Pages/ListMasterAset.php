<?php
namespace App\Filament\Resources\MasterAsetResource\Pages;
use App\Filament\Resources\MasterAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListMasterAset extends ListRecords { protected static string $resource = MasterAsetResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }
