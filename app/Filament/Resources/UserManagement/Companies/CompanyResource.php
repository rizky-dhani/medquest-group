<?php

namespace App\Filament\Resources\UserManagement\Companies;

use App\Filament\Resources\UserManagement\Companies\Schemas\CompanyForm;
use App\Filament\Resources\UserManagement\Companies\Tables\CompanyTable;
use App\Models\Company;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompanies::route('/'),
        ];
    }
}
