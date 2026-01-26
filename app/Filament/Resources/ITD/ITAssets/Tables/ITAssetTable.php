<?php

namespace App\Filament\Resources\ITD\ITAssets\Tables;

use Filament\Actions;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ITAssetTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at'))
            ->emptyStateHeading('No IT Assets Found')
            ->columns([
                TextColumn::make('asset_name')
                    ->label('Asset Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_code')
                    ->label('Asset Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_serial_number')
                    ->label('Serial Number')
                    ->getStateUsing(fn ($record) => $record->asset_serial_number ? strtoupper($record->asset_serial_number) : 'N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_year_bought')
                    ->label('Asset Year')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_condition')
                    ->label('Condition')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_payment_status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.name')
                    ->label('User')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pic_id')
                    ->label('Created By')
                    ->formatStateUsing(function ($record) {
                        $initial = $record->user->employee->initial ?? '';

                        return $initial.' '.strtoupper($record->created_at->format('d M Y'));
                    })
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name',
                        fn (Builder $query) => $query->orderBy('name'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->initials} - {$record->name}")
                    ->preload()
                    ->searchable(),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}")
                    ->preload()
                    ->searchable(),
                SelectFilter::make('asset_condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'First Hand' => 'First Hand',
                        'Used' => 'Used',
                        'Defect' => 'Defect',
                        'Disposed' => 'Disposed',
                    ]),
                SelectFilter::make('asset_payment_status')
                    ->label('Status Payment')
                    ->options([
                        'Pending Paid' => 'Pending Paid',
                        'Paid' => 'Paid',
                    ]),
                Filter::make('asset_status')
                    ->form([
                        Checkbox::make('available')
                            ->label('Available'),
                        Checkbox::make('in_use')
                            ->label('In Use'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['available'] ?? false, fn ($query) => $query->whereNull('asset_user_id'))
                            ->when($data['in_use'] ?? false, fn ($query) => $query->whereNotNull('asset_user_id'));
                    }),
            ])
            ->recordActions([
                Actions\ActionGroup::make([
                    Actions\Action::make('Detail')
                        ->label('Detail')
                        ->color('warning')
                        ->icon('heroicon-o-information-circle')
                        ->url(fn ($record) => route('assets.show', ['assetId' => $record->assetId]))
                        ->openUrlInNewTab(),
                    Actions\ViewAction::make(),
                    Actions\EditAction::make(),
                    Actions\DeleteAction::make()
                        ->modalHeading('Delete Asset')
                        ->successNotificationTitle('Asset deleted successfully.')
                        ->requiresConfirmation(),
                ])
                    ->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Assets')
                        ->successNotificationTitle('Assets deleted successfully.')
                        ->requiresConfirmation(),
                    Actions\BulkAction::make('export_pdf')
                        ->label('Export to PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            session(['export_asset_ids' => $ids]);

                            return redirect()->route('assets.bulk-export-pdf.export');
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
