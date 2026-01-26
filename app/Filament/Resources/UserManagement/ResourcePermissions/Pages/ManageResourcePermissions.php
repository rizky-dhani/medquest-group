<?php

namespace App\Filament\Resources\UserManagement\ResourcePermissions\Pages;

use App\Filament\Resources\UserManagement\ResourcePermissions\ResourcePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageResourcePermissions extends ManageRecords
{
    protected static string $resource = ResourcePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data) {
                    $data['permissionId'] = Str::orderedUuid();

                    return $data;
                }),
        ];
    }
}
