<?php

namespace App\Filament\Resources\UserManagement\Users;

use App\Filament\Resources\UserManagement\Users\Schemas\UserForm;
use App\Filament\Resources\UserManagement\Users\Tables\UserTable;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'ITD']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
