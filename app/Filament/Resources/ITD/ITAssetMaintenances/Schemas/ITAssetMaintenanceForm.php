<?php

namespace App\Filament\Resources\ITD\ITAssetMaintenances\Schemas;

use App\Models\Employee;
use App\Models\ITAsset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ITAssetMaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->columns(2)
                    ->components([
                        DatePicker::make('maintenance_date')
                            ->label('Maintenance Date')
                            ->required()
                            ->default(now()),
                        Select::make('asset_id')
                            ->label('Asset Code')
                            ->relationship('asset', 'asset_code')
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                $userInitial = $record->employee?->initial ?? 'N/A';

                                return "{$record->asset_code} - {$userInitial} - {$record->asset_name}";
                            })
                            ->afterStateUpdated(function ($set, $state) {
                                $asset = ITAsset::find($state);
                                if ($asset && $asset->asset_user_id) {
                                    $set('employee_id', $asset->asset_user_id);
                                    $set('initial', $asset->employee->initial ?? '');
                                } else {
                                    $set('employee_id', null);
                                    $set('initial', '');
                                }
                            })
                            ->live()
                            ->preload()
                            ->required()
                            ->searchable(),
                    ]),
                Section::make('Employee Details')
                    ->columns(3)
                    ->components([
                        Select::make('employee_id')
                            ->label('User')
                            ->relationship('employee', 'name')
                            ->preload()
                            ->live()
                            ->searchable()
                            ->afterStateUpdated(function ($set, $state) {
                                $employee = Employee::find($state);
                                if ($employee) {
                                    $set('initial', $employee->initial);
                                }
                            }),
                        TextInput::make('initial')
                            ->label('Initial')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($set, $get) {
                                $employee = $get('employee_id') ? Employee::find($get('employee_id')) : null;
                                $set('initial', $employee->initial ?? '');
                            }),
                        Select::make('division_id')
                            ->label('Division')
                            ->relationship('division', 'name')
                            ->preload()
                            ->searchable(),
                    ]),
                Section::make('Maintenance Details')
                    ->components([
                        Textarea::make('maintenance_condition')
                            ->label('Condition/Problem')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('maintenance_repair')
                            ->label('Maintenance/Repair')
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->components([
                                TimePicker::make('maintenance_start_time')
                                    ->label('Start Time')
                                    ->required()
                                    ->seconds(false),
                                TimePicker::make('maintenance_end_time')
                                    ->label('Finish Time')
                                    ->required()
                                    ->seconds(false),
                            ]),
                    ]),
            ]);
    }
}
