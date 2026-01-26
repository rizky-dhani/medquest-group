<?php

namespace App\Filament\Resources\UserManagement\Users\Tables;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class UserTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete User')
                    ->successNotificationTitle('User deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Users')
                        ->successNotificationTitle('Users deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
