<?php

namespace App\Filament\Resources\Employee\EmployeeDivisions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class EmployeeDivisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->components([
                        TextInput::make('name')
                            ->label('Division Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('abbreviation')
                            ->label('Initial')
                            ->required()
                            ->maxLength(3),
                    ]),
            ]);
    }
}
