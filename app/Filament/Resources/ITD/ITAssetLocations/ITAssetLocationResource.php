<?php

namespace App\Filament\Resources\ITD\ITAssetLocations;

use App\Filament\Resources\ITD\ITAssetLocations\Schemas\ITAssetLocationForm;
use App\Filament\Resources\ITD\ITAssetLocations\Tables\ITAssetLocationTable;
use App\Models\ITAssetLocation;
use App\Traits\HasResourceRolePermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ITAssetLocationResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = ITAssetLocation::class;

    protected static ?string $slug = 'it-asset-locations';

    protected static string|\UnitEnum|null $navigationGroup = ' ITD';

    protected static ?string $navigationLabel = 'Locations';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationParentItem = 'IT Assets';

    protected static ?string $modelLabel = 'IT Asset Location';

    protected static ?string $pluralModelLabel = 'IT Asset Locations';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ITAssetLocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ITAssetLocationTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageITAssetLocations::route('/'),
        ];
    }
}
