<?php

namespace App\Filament\Resources\ITD\ITAssets\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section as InfolistSection;
use Filament\Schemas\Schema;

class ITAssetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                InfolistSection::make('Asset Details')
                    ->columns(4)
                    ->components([
                        TextEntry::make('asset_name')
                            ->label('Asset Name'),
                        TextEntry::make('asset_code')
                            ->label('Asset Code'),
                        TextEntry::make('asset_serial_number')
                            ->label('Serial Number')
                            ->getStateUsing(fn ($record) => $record->asset_serial_number ? strtoupper($record->asset_serial_number) : 'N/A'),
                        TextEntry::make('asset_year_bought')
                            ->label('Year Bought'),
                        TextEntry::make('asset_brand')
                            ->label('Brand')
                            ->getStateUsing(fn ($record) => $record->asset_brand ? strtoupper($record->asset_brand) : 'N/A'),
                        TextEntry::make('asset_model')
                            ->label('Model')
                            ->getStateUsing(fn ($record) => $record->asset_model ? strtoupper($record->asset_model) : 'N/A'),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('asset_price')
                            ->label('Price')
                            ->formatStateUsing(fn ($state) => $state ? 'Rp. '.number_format($state, 0, ',', '.') : 'N/A'),
                        TextEntry::make('asset_condition')
                            ->label('Condition'),
                        TextEntry::make('asset_payment_status')
                            ->label('Status Payment'),
                        TextEntry::make('location.name')
                            ->label('Location'),
                        TextEntry::make('employee.name')
                            ->label('Asset User')
                            ->placeholder('N/A'),
                        TextEntry::make('asset_notes')
                            ->label('Notes')
                            ->limit(100),
                        TextEntry::make('asset_remarks')
                            ->label('Remark')
                            ->limit(100),
                    ]),
            ]);
    }
}
