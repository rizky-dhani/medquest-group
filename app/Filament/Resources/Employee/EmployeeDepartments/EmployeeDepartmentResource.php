<?php

namespace App\Filament\Resources\Employee\EmployeeDepartments;

use App\Filament\Resources\Employee\EmployeeDepartments\Schemas\EmployeeDepartmentForm;
use App\Filament\Resources\Employee\EmployeeDepartments\Schemas\EmployeeDepartmentInfolist;
use App\Filament\Resources\Employee\EmployeeDepartments\Tables\EmployeeDepartmentTable;
use App\Models\EmployeeDepartment;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EmployeeDepartmentResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = EmployeeDepartment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Departments';

    protected static ?string $navigationParentItem = 'Employees';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function form(Schema $schema): Schema
    {
        return EmployeeDepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeDepartmentTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmployeeDepartmentInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DivisionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEmployeeDepartment::route('/'),
            'view' => Pages\ViewEmployeeDepartment::route('/{record}'),
        ];
    }
}
