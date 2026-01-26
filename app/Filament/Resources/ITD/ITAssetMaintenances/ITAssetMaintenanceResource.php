<?php

namespace App\Filament\Resources\ITD\ITAssetMaintenances;

use App\Filament\Resources\ITD\ITAssetMaintenances\Schemas\ITAssetMaintenanceForm;
use App\Filament\Resources\ITD\ITAssetMaintenances\Tables\ITAssetMaintenanceTable;
use App\Models\ITAssetMaintenance;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ITAssetMaintenanceResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = ITAssetMaintenance::class;

    protected static ?string $navigationLabel = 'Maintenance Log';

    protected static ?string $modelLabel = 'IT Asset Maintenance Log';

    protected static ?string $pluralModelLabel = 'IT Asset Maintenance Logs';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'it-asset-maintenance-log';

    protected static ?string $navigationParentItem = 'IT Assets';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = ' ITD';

    public static function getBreadcrumb(): string
    {
        return 'IT Asset Maintenance Log';
    }

    public static function form(Schema $schema): Schema
    {
        return ITAssetMaintenanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ITAssetMaintenanceTable::configure($table);
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
            'index' => Pages\ListITAssetMaintenances::route('/'),
            'create' => Pages\CreateITAssetMaintenance::route('/create'),
            'edit' => Pages\EditITAssetMaintenance::route('/{record}/edit'),
        ];
    }
}
