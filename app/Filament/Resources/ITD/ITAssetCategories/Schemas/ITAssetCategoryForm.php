<?php

namespace App\Filament\Resources\ITD\ITAssetCategories\Schemas;

use App\Models\ITAssetCategory;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ITAssetCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ITAssetCategory::class, 'code', ignoreRecord: true),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('remarks')
                    ->label('Remarks')
                    ->nullable()
                    ->maxLength(500)
                    ->autosize()
                    ->columnSpanFull(),
            ]);
    }
}
