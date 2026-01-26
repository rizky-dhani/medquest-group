<?php

namespace App\Filament\Resources\Employee\Employees\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Account')
                    ->description('Select the user account associated with this employee if they need login access.')
                    ->components([
                        Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select User Account'),
                    ]),
                Section::make('Employee Information')
                    ->columns(3)
                    ->components([
                        TextInput::make('name')
                            ->label('Employee Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('initial')
                            ->label('Initial')
                            ->unique(Employee::class, 'initial', ignoreRecord: true)
                            ->maxLength(3),
                        TextInput::make('employee_number')
                            ->label('Employee Number')
                            ->required()
                            ->maxLength(10)
                            ->unique(Employee::class, 'employee_number', ignoreRecord: true),
                    ]),
            ]);
    }
}
