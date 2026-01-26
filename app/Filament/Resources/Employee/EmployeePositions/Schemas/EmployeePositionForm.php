<?php

namespace App\Filament\Resources\Employee\EmployeePositions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeePositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Position Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}
