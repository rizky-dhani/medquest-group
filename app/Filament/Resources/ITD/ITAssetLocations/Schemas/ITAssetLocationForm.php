<?php

namespace App\Filament\Resources\ITD\ITAssetLocations\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ITAssetLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Hidden::make('locationId')
                    ->default(fn () => Str::orderedUuid()->toString()),
            ]);
    }
}
