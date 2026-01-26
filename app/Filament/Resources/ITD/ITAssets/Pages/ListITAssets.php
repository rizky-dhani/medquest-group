<?php

namespace App\Filament\Resources\ITD\ITAssets\Pages;

use App\Filament\Resources\ITD\ITAssets\ITAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListITAssets extends ListRecords
{
    protected static string $resource = ITAssetResource::class;

    protected static ?string $title = 'IT Assets';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New IT Asset'),
        ];
    }
}
