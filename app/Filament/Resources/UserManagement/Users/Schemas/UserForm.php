<?php

namespace App\Filament\Resources\UserManagement\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Account Details')
                    ->components([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('roles')
                            ->label('Roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->required()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
