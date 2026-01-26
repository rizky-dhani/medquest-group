<?php

namespace App\Filament\Resources\ITD\ITAssetUsageHistories;

use App\Filament\Resources\ITD\ITAssetUsageHistories\Schemas\ITAssetUsageHistoryForm;
use App\Filament\Resources\ITD\ITAssetUsageHistories\Tables\ITAssetUsageHistoryTable;
use App\Models\ITAssetUsageHistory;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ITAssetUsageHistoryResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = ITAssetUsageHistory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = ' ITD';

    protected static ?string $navigationParentItem = 'IT Assets';

    protected static ?string $navigationLabel = 'Usage History';

    protected static ?string $modelLabel = 'IT Asset Usage History';

    protected static ?string $pluralModelLabel = 'IT Asset Usage Histories';

    protected static ?string $slug = 'it-asset-usage-histories';

    protected static ?int $navigationSort = 1;

    public static function getBreadcrumb(): string
    {
        return 'IT Asset Usage History';
    }

    public static function form(Schema $schema): Schema
    {
        return ITAssetUsageHistoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ITAssetUsageHistoryTable::configure($table);
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
            'index' => Pages\ListITAssetUsageHistories::route('/'),
            'create' => Pages\CreateITAssetUsageHistory::route('/create'),
            'edit' => Pages\EditITAssetUsageHistory::route('/{record}/edit'),
        ];
    }
}
