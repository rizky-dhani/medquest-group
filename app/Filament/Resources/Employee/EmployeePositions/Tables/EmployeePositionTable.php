<?php

namespace App\Filament\Resources\Employee\EmployeePositions\Tables;

use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeePositionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Position Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Position')
                    ->successNotificationTitle('Position deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Positions')
                        ->successNotificationTitle('Positions deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
