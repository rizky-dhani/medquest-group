<?php

namespace App\Filament\Resources\UserManagement\ResourcePermissions;

use App\Filament\Resources\UserManagement\ResourcePermissions\Schemas\ResourcePermissionForm;
use App\Filament\Resources\UserManagement\ResourcePermissions\Tables\ResourcePermissionTable;
use App\Models\ResourcePermission;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ResourcePermissionResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = ResourcePermission::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Resource Permissions';

    public static function form(Schema $schema): Schema
    {
        return ResourcePermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResourcePermissionTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageResourcePermissions::route('/'),
        ];
    }
}
