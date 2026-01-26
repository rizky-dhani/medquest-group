<?php

namespace App\Filament\Resources\ITD\ITAssetUsageHistories\Tables;

use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ITAssetUsageHistoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Usage History Found')
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('asset.asset_code')
                    ->label('Asset Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asset.asset_name')
                    ->label('Asset Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('location.name')
                    ->label('Location')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('employee.name')
                    ->label('Assigned To')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('usage_start_date')
                    ->label('Start Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('usage_end_date')
                    ->label('End Date')
                    ->date('d M Y')
                    ->placeholder('Active')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name)
                    ->searchable()
                    ->preload(),
                SelectFilter::make('employee_id')
                    ->label('Assigned To')
                    ->relationship('employee', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => ($record->initial ?? 'N/A').' - '.$record->name)
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Actions\Action::make('view_asset')
                    ->label('View Asset')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('filament.admin.resources.it-assets.view', ['record' => $record->asset->assetId])),
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Usage History')
                    ->successNotificationTitle('Usage history deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Histories')
                        ->successNotificationTitle('Usage histories deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
