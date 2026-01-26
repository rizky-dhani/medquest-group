<?php

namespace App\Filament\Resources\ITD\ITAssetMaintenances\Tables;

use Carbon\Carbon;
use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ITAssetMaintenanceTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at'))
            ->emptyStateHeading('No IT Asset Maintenance Logs Found')
            ->columns([
                TextColumn::make('maintenance_date')
                    ->label('Maintenance Date')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => strtoupper(Carbon::parse($state)->format('d M Y'))),
                TextColumn::make('asset.asset_code')
                    ->label('Asset Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.initial')
                    ->label('User')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('division.abbreviation')
                    ->label('Division')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\ViewAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Maintenance Log')
                    ->successNotificationTitle('Maintenance log deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Logs')
                        ->successNotificationTitle('Maintenance logs deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
