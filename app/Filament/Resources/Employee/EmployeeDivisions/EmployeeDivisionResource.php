<?php

namespace App\Filament\Resources\Employee\EmployeeDivisions;

use App\Filament\Resources\Employee\EmployeeDivisions\Schemas\EmployeeDivisionForm;
use App\Filament\Resources\Employee\EmployeeDivisions\Tables\EmployeeDivisionTable;
use App\Models\EmployeeDivision;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EmployeeDivisionResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = EmployeeDivision::class;

    protected static ?string $navigationLabel = 'Divisions';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationParentItem = 'Employees';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function form(Schema $schema): Schema
    {
        return EmployeeDivisionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeDivisionTable::configure($table);
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
            'index' => Pages\ListEmployeeDivisions::route('/'),
            'create' => Pages\CreateEmployeeDivision::route('/create'),
            'edit' => Pages\EditEmployeeDivision::route('/{record}/edit'),
        ];
    }
}
