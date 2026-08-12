<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Enums\MaxWidth;

class EditProfile extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $title = 'Edit Profile';

    protected string $view = 'filament.pages.edit-profile';

    protected static ?MaxWidth $maxWidth = MaxWidth::TwoExtraLarge;

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();

        $this->data = [
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    public function content(Schema $schema): Schema
    {
        $user = auth()->user();

        return $schema
            ->components([
                Forms\Components\Section::make('Account Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Employee Information')
                    ->schema([
                        Forms\Components\TextInput::make('employee_name')
                            ->label('Employee Name')
                            ->default($user->employee?->name ?? 'N/A')
                            ->disabled(),
                        Forms\Components\TextInput::make('employee_initial')
                            ->label('Initial')
                            ->default($user->employee?->initial ?? 'N/A')
                            ->disabled(),
                        Forms\Components\TextInput::make('employee_number')
                            ->label('Employee Number')
                            ->default($user->employee?->employee_number ?? 'N/A')
                            ->disabled(),
                        Forms\Components\TextInput::make('division_name')
                            ->label('Division')
                            ->default($user->employee?->division?->name ?? 'N/A')
                            ->disabled(),
                        Forms\Components\TextInput::make('position_name')
                            ->label('Position')
                            ->default($user->employee?->position?->name ?? 'N/A')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Change Password')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->nullable()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->nullable()
                            ->maxLength(255)
                            ->same('password'),
                    ])->columns(2),
            ]);
    }

    public function save(): void
    {
        $data = $this->data;

        $user = auth()->user();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'] ?? $user->password,
        ]);

        Notification::make()
            ->title('Profile updated successfully.')
            ->success()
            ->send();
    }
}
