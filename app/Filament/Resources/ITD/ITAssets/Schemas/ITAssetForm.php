<?php

namespace App\Filament\Resources\ITD\ITAssets\Schemas;

use App\Models\ITAssetCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ITAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('asset_name')
                    ->label('Asset Name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('asset_name', strtoupper($state))),
                Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name',
                        fn (Builder $query) => $query->orderBy('name'))
                    ->columnSpanFull()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->initials} - {$record->name}")
                    ->preload()
                    ->searchable()
                    ->required(),
                Select::make('asset_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}")
                    ->preload()
                    ->live()
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $category = ITAssetCategory::find($state);
                        $set('asset_remarks', $category?->remarks ?? '');
                    }),
                DatePicker::make('asset_year_bought')
                    ->label('Asset Year')
                    ->native(false)
                    ->displayFormat('Y')
                    ->format('Y')
                    ->closeOnDateSelection()
                    ->default(now())
                    ->required(),
                Grid::make(3)
                    ->components([
                        TextInput::make('asset_brand')
                            ->label('Brand')
                            ->afterStateUpdated(fn ($state, callable $set) => $set('asset_brand', strtoupper($state))),
                        TextInput::make('asset_model')
                            ->label('Model')
                            ->maxLength(100)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('asset_model', strtoupper($state))),
                        TextInput::make('asset_serial_number')
                            ->label('Serial Number')
                            ->maxLength(100)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('asset_serial_number', strtoupper($state))),
                    ]),
                TextInput::make('asset_price')
                    ->label('Price')
                    ->prefix('Rp')
                    ->live()
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 0, ',', '.') : '')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $cleanValue = preg_replace('/[^0-9]/', '', $state);
                            $formattedValue = number_format((int) $cleanValue, 0, ',', '.');
                            $set('asset_price', $formattedValue);
                        }
                    })
                    ->dehydrateStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null),
                Select::make('asset_condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'First Hand' => 'First Hand',
                        'Used' => 'Used',
                        'Defect' => 'Defect',
                        'Disposed' => 'Disposed',
                    ])
                    ->required(),
                Select::make('asset_payment_status')
                    ->label('Status Payment')
                    ->options([
                        'Pending Paid' => 'Pending Paid',
                        'Paid' => 'Paid',
                    ])
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('asset_notes')
                    ->label('History/Notes')
                    ->maxLength(500)
                    ->columnSpanFull(),
                Textarea::make('asset_remarks')
                    ->label('Remark')
                    ->maxLength(500)
                    ->autosize()
                    ->live()
                    ->columnSpanFull(),
            ]);
    }
}
