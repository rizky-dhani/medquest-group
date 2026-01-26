<?php

namespace App\Filament\Resources\ITD\ITAssetUsageHistories\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ITAssetUsageHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('employee_id')
                    ->label('Assign To')
                    ->relationship('employee', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => ($record->initial ?? 'N/A').' - '.$record->name)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionModalHeading('Add New Employee')
                    ->createOptionForm([
                        Hidden::make('employeeId')
                            ->default(fn () => (string) Str::orderedUuid()),
                        TextInput::make('name')
                            ->label('Employee Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('initial')
                            ->label('Initial')
                            ->maxLength(3)
                            ->unique(Employee::class, 'initial', ignoreRecord: true),
                        TextInput::make('employee_number')
                            ->label('Employee Number')
                            ->maxLength(10)
                            ->unique(Employee::class, 'employee_number', ignoreRecord: true),
                    ]),
                Select::make('asset_location_id')
                    ->label('Location')
                    ->relationship('location', 'name', fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('usage_start_date')
                    ->label('Start Date')
                    ->default(now())
                    ->required(),
                DatePicker::make('usage_end_date')
                    ->label('End Date'),
            ]);
    }
}
