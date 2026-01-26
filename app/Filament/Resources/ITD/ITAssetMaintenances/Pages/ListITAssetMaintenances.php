<?php

namespace App\Filament\Resources\ITD\ITAssetMaintenances\Pages;

use App\Filament\Resources\ITD\ITAssetMaintenances\ITAssetMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListITAssetMaintenances extends ListRecords
{
    protected static string $resource = ITAssetMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New IT Maintenance Log'),
        ];
    }
}
