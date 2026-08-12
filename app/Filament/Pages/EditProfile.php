<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class EditProfile extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $title = 'Edit Profile';

    protected static string $view = 'filament.pages.edit-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                            ->default(fn () => auth()->user()->employee?->name ?? 'N/A')
                            ->disabled(),
                        Forms\Components\TextInput::make('employee_initial')
                            ->label('Initial')
                            ->default(fn () => auth()->user()->employee?->initial ?? 'N/A')
                            ->disabled(),
                        Forms\Components\TextInput::make('employee_number')
                            ->label('Employee Number')
                            ->default(fn () => auth()->user()->employee?->employee_number ?? 'N/A')
                            ->disabled(),
                        Forms\Components\TextInput::make('division_name')
                            ->label('Division')
                            ->default(fn () => auth()->user()->employee?->division?->name ?? 'N/A')
                            ->disabled(),
                        Forms\Components\TextInput::make('position_name')
                            ->label('Position')
                            ->default(fn () => auth()->user()->employee?->position?->name ?? 'N/A')
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
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

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
