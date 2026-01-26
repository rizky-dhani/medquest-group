<?php

namespace App\Filament\Resources\Employee\EmployeeDepartments\Schemas;

use App\Models\EmployeeDepartment;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeDepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Department Name')
                    ->required()
                    ->unique(EmployeeDepartment::class, 'name', ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}
