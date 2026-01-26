<?php

namespace App\Filament\Resources\UserManagement\ResourcePermissions\Tables;

use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResourcePermissionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('resource_name')
                    ->label('Model')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Permission')
                    ->successNotificationTitle('Permission deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Permissions')
                        ->successNotificationTitle('Permissions deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
