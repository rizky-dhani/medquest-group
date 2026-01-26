<?php

namespace App\Filament\Resources\Employee\EmployeeDepartments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class EmployeeDepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Department Details')
                    ->components([
                        TextEntry::make('name')
                            ->label('Department Name')
                            ->size(TextEntrySize::Large)
                            ->weight(FontWeight::Bold),
                    ]),
            ]);
    }
}
