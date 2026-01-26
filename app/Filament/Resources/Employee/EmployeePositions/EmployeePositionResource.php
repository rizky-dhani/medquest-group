<?php

namespace App\Filament\Resources\Employee\EmployeePositions;

use App\Filament\Resources\Employee\EmployeePositions\Schemas\EmployeePositionForm;
use App\Filament\Resources\Employee\EmployeePositions\Tables\EmployeePositionTable;
use App\Models\EmployeePosition;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EmployeePositionResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = EmployeePosition::class;

    protected static ?string $navigationLabel = 'Positions';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationParentItem = 'Employees';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function form(Schema $schema): Schema
    {
        return EmployeePositionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeePositionTable::configure($table);
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
            'index' => Pages\ManageEmployeePositions::route('/'),
        ];
    }
}
