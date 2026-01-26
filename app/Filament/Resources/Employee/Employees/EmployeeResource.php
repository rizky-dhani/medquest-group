<?php

namespace App\Filament\Resources\Employee\Employees;

use App\Filament\Resources\Employee\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\Employee\Employees\Tables\EmployeeTable;
use App\Models\Employee;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = Employee::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEmployees::route('/'),
        ];
    }
}
