<?php

namespace App\Filament\Resources\Employee\EmployeeDivisions\Tables;

use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class EmployeeDivisionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Division Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('abbreviation')
                    ->label('Initial')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\Action::make('addDepartment')
                    ->label('Set Department')
                    ->icon('heroicon-o-plus')
                    ->color('info')
                    ->form([
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->department_id = $data['department_id'] ?? null;
                        $record->save();
                    })
                    ->modalHeading('Set Division Department')
                    ->modalSubmitActionLabel('Save')
                    ->successNotificationTitle('Department updated successfully'),
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Division')
                    ->successNotificationTitle('Division deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('addToDepartment')
                        ->label('Set Department for Selected')
                        ->icon('heroicon-o-plus')
                        ->form([
                            Select::make('department_id')
                                ->label('Department')
                                ->relationship('department', 'name')
                                ->searchable()
                                ->preload(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $record) {
                                $record->department_id = $data['department_id'] ?? null;
                                $record->save();
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading('Set Department for Selected Divisions')
                        ->successNotificationTitle('Departments updated successfully.'),
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Divisions')
                        ->successNotificationTitle('Divisions deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
