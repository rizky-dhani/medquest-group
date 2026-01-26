<?php

namespace App\Filament\Resources\ITD\ITAssetCategories;

use App\Filament\Resources\ITD\ITAssetCategories\Schemas\ITAssetCategoryForm;
use App\Filament\Resources\ITD\ITAssetCategories\Tables\ITAssetCategoryTable;
use App\Models\ITAssetCategory;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ITAssetCategoryResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = ITAssetCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'it-asset-categories';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationParentItem = 'IT Assets';

    protected static string|\UnitEnum|null $navigationGroup = ' ITD';

    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $modelLabel = 'IT Asset Category';

    protected static ?string $pluralModelLabel = 'IT Asset Categories';

    public static function form(Schema $schema): Schema
    {
        return ITAssetCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ITAssetCategoryTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageITAssetCategories::route('/'),
        ];
    }
}
