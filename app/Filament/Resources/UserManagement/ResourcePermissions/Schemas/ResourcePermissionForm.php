<?php

namespace App\Filament\Resources\UserManagement\ResourcePermissions\Schemas;

use App\Models\ResourcePermission;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ResourcePermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('resource_name')
                    ->label('Resource Name')
                    ->options(function ($record) {
                        $allResources = collect(Filament::getResources())
                            ->mapWithKeys(fn ($resource) => [
                                $resource::getModel() => class_basename($resource::getModel()),
                            ]);

                        $usedResourceNames = ResourcePermission::query()
                            ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                            ->pluck('resource_name')
                            ->toArray();

                        return $allResources->except($usedResourceNames);
                    })
                    ->searchable()
                    ->required(),
                Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->required(),
            ]);
    }
}
