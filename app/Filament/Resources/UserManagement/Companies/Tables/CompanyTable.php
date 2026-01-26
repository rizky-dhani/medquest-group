<?php

namespace App\Filament\Resources\UserManagement\Companies\Tables;

use Filament\Actions;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompanyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('name'))
            ->columns([
                ImageColumn::make('company_logo')
                    ->label('Logo')
                    ->disk('public')
                    ->defaultImageUrl(asset('assets/images/Medquest-Favicon.png'))
                    ->size(50),
                TextColumn::make('name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('initials')
                    ->label('Initials')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company_code')
                    ->label('Company Code')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Company')
                    ->successNotificationTitle('Company deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Companies')
                        ->successNotificationTitle('Companies deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
