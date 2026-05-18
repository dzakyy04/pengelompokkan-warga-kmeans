<?php
namespace App\Filament\Resources\MasterKondisiRumahResource\Pages;
use App\Filament\Resources\MasterKondisiRumahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListMasterKondisiRumah extends ListRecords { protected static string $resource = MasterKondisiRumahResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }
