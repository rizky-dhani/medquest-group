<?php

namespace App\Filament\Resources\Employee\Employees\Pages;

use App\Filament\Resources\Employee\Employees\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageEmployees extends ManageRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Employee')
                ->modalHeading('Create New Employee')
                ->successNotificationTitle('Employee created successfully')
                ->mutateFormDataUsing(function (array $data): array {
                    // Set the initial division to 'Head Office' if not set
                    $data['employeeId'] = Str::orderedUuid();

                    return $data;
                }),
            Actions\Action::make('importExcel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Excel File')
                        ->disk('local')
                        // ->acceptedFileTypes(['.xlsx', '.xls'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = \Storage::path($data['file']);
                        \Maatwebsite\Excel\Facades\Excel::import(
                            new \App\Imports\EmployeesImport,
                            $filePath
                        );
                        \Filament\Notifications\Notification::make()
                            ->title('Import successful!')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Import failed: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
