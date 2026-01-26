<?php

namespace App\Filament\Resources\Employee\Employees\Tables;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('employee_number'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Employee Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial')
                    ->label('Initial')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_number')
                    ->label('Employee Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Login Access')
                    ->placeholder('No Access')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Employee')
                    ->modalDescription('Are you sure you want to delete this employee? This action cannot be undone.')
                    ->successNotificationTitle('Employee deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Employees')
                        ->successNotificationTitle('Employees deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
