<?php

namespace App\Filament\Resources\UserManagement\Companies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('companyId')
                    ->default(fn () => (string) Str::orderedUuid()),
                FileUpload::make('company_logo')
                    ->label('Company Logo')
                    ->image()
                    ->disk('public')
                    ->directory('companies/logos')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/svg+xml'])
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file, $get): string => str_replace(' ', '_', $get('initials') ?? 'company').'_logo.'.$file->getClientOriginalExtension()
                    )
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Company Name'),
                TextInput::make('initials')
                    ->maxLength(50)
                    ->label('Initials'),
                TextInput::make('company_code')
                    ->label('Company Code')
                    ->maxLength(10)
                    ->placeholder('11')
                    ->default('11'),
            ]);
    }
}
