<?php

namespace App\Filament\Resources\ITD\ITAssets\RelationManagers;

use App\Models\Employee;
use App\Models\ITAssetLocation;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UsageHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'usageHistory';

    public function isReadOnly(): bool
    {
        return false;
    }

    protected static bool $isLazy = true;

    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name)
                    ->default(fn () => $this->getOwnerRecord()->id)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('asset_location_id')
                    ->label('Location')
                    ->relationship('location', 'name', fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->default(fn () => ITAssetLocation::where('name', 'Head Office')->value('id'))
                    ->createOptionModalHeading('Add New Location')
                    ->createOptionForm([
                        Hidden::make('locationId')
                            ->default(fn () => (string) Str::orderedUuid()),
                        TextInput::make('name')
                            ->label('Location Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->preload()
                    ->required(),
                Section::make('Employee Assignment')
                    ->columns(3)
                    ->components([
                        Select::make('employee_id')
                            ->label('Assign To')
                            ->relationship('employee', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => ($record->initial ?? 'N/A').' - '.$record->name)
                            ->searchable(['initial', 'name'])
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
                            ])
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('division_id')
                            ->label('Division')
                            ->relationship('division', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('position_id')
                            ->label('Position')
                            ->relationship('position', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                DatePicker::make('usage_start_date')
                    ->label('Start Date')
                    ->default(now())
                    ->required(),
                DatePicker::make('usage_end_date')
                    ->label('End Date'),
                Hidden::make('usageId')
                    ->default(fn () => (string) Str::orderedUuid()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Usage History Found')
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('usage_start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('usage_end_date')
                    ->label('End Date')
                    ->date()
                    ->placeholder('Active')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Assign Asset')
                    ->successNotificationTitle('Asset Assigned Successfully')
                    ->after(fn ($record) => $this->handleAssetAssignment($record)),
            ])
            ->recordActions([
                Actions\EditAction::make()
                    ->successNotificationTitle('Usage History Updated Successfully')
                    ->after(fn ($record) => $this->handleAssetUpdate($record)),
                Actions\DeleteAction::make()
                    ->modalHeading('Delete Usage History')
                    ->successNotificationTitle('Usage history deleted successfully.')
                    ->requiresConfirmation()
                    ->before(fn ($record) => $this->handleUsageHistoryDeletion($record)),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Histories')
                        ->successNotificationTitle('Usage histories deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    private function getHeadOfficeLocation()
    {
        return ITAssetLocation::where('name', 'Head Office')->first();
    }

    private function moveAssetToHeadOffice($asset)
    {
        $headOfficeLocation = $this->getHeadOfficeLocation();
        if ($headOfficeLocation) {
            $asset->asset_location_id = $headOfficeLocation->id;
            $asset->asset_user_id = null;
        }
    }

    private function handleAssetAssignment($record)
    {
        if (! $record->asset) {
            return;
        }

        $record->asset->asset_location_id = $record->asset_location_id;
        $record->asset->asset_user_id = $record->employee_id;

        $previousUsage = $record->asset
            ->usageHistory()
            ->where('id', '<', $record->id)
            ->orderByDesc('usage_start_date')
            ->orderByDesc('id')
            ->first();

        if ($previousUsage && is_null($previousUsage->usage_end_date)) {
            $previousUsage->usage_end_date = $record->usage_start_date;
            $previousUsage->save();
        }

        $this->updateAssetCondition($record);
        $record->asset->save();
    }

    private function updateAssetCondition($record)
    {
        $asset = $record->asset;
        if ($asset->asset_condition === 'New') {
            if ($record->usage_end_date !== null) {
                $this->moveAssetToHeadOffice($asset);
            }
            $asset->asset_condition = 'First Hand';
        } elseif ($asset->asset_condition === 'First Hand') {
            if ($asset->usageHistory()->count() >= 2) {
                if ($record->usage_end_date !== null) {
                    $this->moveAssetToHeadOffice($asset);
                }
                $asset->asset_condition = 'Used';
            }
        }
    }

    private function handleAssetUpdate($record)
    {
        if (! $record->asset) {
            return;
        }

        if (! is_null($record->usage_end_date)) {
            $this->moveAssetToHeadOffice($record->asset);
        } else {
            $record->asset->asset_location_id = $record->asset_location_id;
            $record->asset->asset_user_id = $record->employee_id;
        }
        $record->asset->save();
    }

    private function handleUsageHistoryDeletion($record)
    {
        if (! $record->asset) {
            return;
        }

        $previousUsage = $record->asset
            ->usageHistory()
            ->where('id', '<', $record->id)
            ->orderByDesc('usage_start_date')
            ->orderByDesc('id')
            ->first();

        $record->asset->asset_user_id = $previousUsage?->employee_id;
        $record->asset->asset_location_id = $previousUsage?->asset_location_id;
        $record->asset->save();

        if ($previousUsage && $this->isLatestUsage($record)) {
            $previousUsage->usage_end_date = null;
            $previousUsage->save();
        }
    }

    private function isLatestUsage($record)
    {
        $latestUsage = $record->asset
            ->usageHistory()
            ->where('id', '!=', $record->id)
            ->orderByDesc('usage_start_date')
            ->orderByDesc('id')
            ->first();

        return ! $latestUsage || $record->usage_start_date >= $latestUsage->usage_start_date;
    }
}
