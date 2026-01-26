<?php

namespace App\Filament\Resources\ITD\ITAssets;

use App\Filament\Resources\ITD\ITAssets\RelationManagers\UsageHistoryRelationManager;
use App\Filament\Resources\ITD\ITAssets\Schemas\ITAssetForm;
use App\Filament\Resources\ITD\ITAssets\Schemas\ITAssetInfolist;
use App\Filament\Resources\ITD\ITAssets\Tables\ITAssetTable;
use App\Models\ITAsset;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ITAssetResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = ITAsset::class;

    protected static ?string $navigationLabel = 'IT Assets';

    protected static ?string $slug = 'it-assets';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = ' ITD';

    public static function getBreadcrumb(): string
    {
        return 'IT Assets';
    }

    public static function form(Schema $schema): Schema
    {
        return ITAssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ITAssetTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ITAssetInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            UsageHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListITAssets::route('/'),
            'create' => Pages\CreateITAsset::route('/create'),
            'edit' => Pages\EditITAsset::route('/{record}/edit'),
            'view' => Pages\ViewITAsset::route('/{record}'),
        ];
    }
}
