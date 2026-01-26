<?php

namespace App\Filament\Resources\ITD\ITAssetLocations\Tables;

use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ITAssetLocationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Locations Found')
            ->columns([
                TextColumn::make('name')
                    ->label('Location Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Location')
                    ->successNotificationTitle('Location deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Locations')
                        ->successNotificationTitle('Locations deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
