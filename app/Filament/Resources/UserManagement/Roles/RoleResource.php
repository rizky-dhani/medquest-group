<?php

namespace App\Filament\Resources\UserManagement\Roles;

use App\Filament\Resources\UserManagement\Roles\Schemas\RoleForm;
use App\Filament\Resources\UserManagement\Roles\Tables\RoleTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-lock-closed';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['Super Admin']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoleTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}
