<?php

namespace App\Filament\Resources\Employee\EmployeeDepartments\RelationManagers;

use App\Models\EmployeeDivision;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DivisionRelationManager extends RelationManager
{
    protected static string $relationship = 'division';

    public function isReadOnly(): bool
    {
        return false;
    }

    protected static bool $isLazy = true;

    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                TextInput::make('name')
                    ->label('Division Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('abbreviation')
                    ->label('Initial')
                    ->required()
                    ->maxLength(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Division Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('abbreviation')
                    ->label('Initial')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\Action::make('assignDivision')
                    ->label('Assign Division')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Select::make('divisions')
                            ->label('Select Divisions')
                            ->multiple()
                            ->options(
                                EmployeeDivision::query()
                                    ->pluck('name', 'id')
                            )
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $selectedDivisions = $data['divisions'] ?? [];
                        $departmentId = $this->getOwnerRecord()->id;

                        if (! empty($selectedDivisions)) {
                            EmployeeDivision::whereIn('id', $selectedDivisions)
                                ->update(['department_id' => $departmentId]);
                        }
                    })
                    ->modalHeading('Assign Division to Department')
                    ->successNotificationTitle('Division(s) assigned successfully.'),
                Actions\CreateAction::make()
                    ->modalHeading('Create New Division')
                    ->successNotificationTitle('Division created successfully.'),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Division')
                    ->successNotificationTitle('Division deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Divisions')
                        ->successNotificationTitle('Divisions deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
