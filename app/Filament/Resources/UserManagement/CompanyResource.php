<?php

namespace App\Filament\Resources\UserManagement;

use Filament\Forms;
use Filament\Tables;
use App\Models\Company;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\UserManagement\CompanyResource\Pages;
use App\Filament\Resources\UserManagement\CompanyResource\RelationManagers;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'User Management';  

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('companyId')
                    ->default(fn () => (string) \Illuminate\Support\Str::orderedUuid()),
                FileUpload::make('company_logo')
                    ->label('Company Logo')
                    ->image()
                    ->disk('public')
                    ->directory('companies/logos')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/svg+xml'])
                    ->storeFileNamesIn('original_filename')
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file, $get): string => str_replace(' ', '_', $get('initials') ?? 'company') . '_logo.' . $file->getClientOriginalExtension()
                    )
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Company Name'),
                TextInput::make('initials')
                    ->maxLength(50)
                    ->label('Initials'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->orderBy('name');
            })
            ->columns([
                ImageColumn::make('company_logo')
                    ->label('Company Logo')
                    ->disk('public')
                    ->defaultImageUrl(asset('assets/images/Medquest-Favicon.png'))
                    ->size(50),
                Tables\Columns\TextColumn::make('name')
                    ->label('Company Name'),
                Tables\Columns\TextColumn::make('initials')
                    ->label('Initials')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompanies::route('/'),
        ];
    }
}
