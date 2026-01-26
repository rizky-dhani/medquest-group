<?php

namespace App\Filament\Resources\UserManagement\Roles\Tables;

use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Role')
                    ->successNotificationTitle('Role deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Roles')
                        ->successNotificationTitle('Roles deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
