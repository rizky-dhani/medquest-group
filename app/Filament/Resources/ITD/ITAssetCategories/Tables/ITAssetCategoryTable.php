<?php

namespace App\Filament\Resources\ITD\ITAssetCategories\Tables;

use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ITAssetCategoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Categories Found')
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Category')
                    ->successNotificationTitle('Category deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Categories')
                        ->successNotificationTitle('Categories deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
