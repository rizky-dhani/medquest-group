<?php

namespace App\Filament\Resources\Employee\EmployeeDepartments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeeDepartmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Department Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this department?')
                    ->modalSubmitActionLabel('Delete Department')
                    ->successNotificationTitle('Department deleted successfully.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these departments?')
                        ->successNotificationTitle('Departments deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
