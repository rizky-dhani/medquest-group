<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $title = 'Edit Profile';

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
                        Forms\Components\TextInput::make('employee.name')
                            ->label('Employee Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('employee.initial')
                            ->label('Initial')
                            ->disabled(),
                        Forms\Components\TextInput::make('employee.employee_number')
                            ->label('Employee Number')
                            ->disabled(),
                        Forms\Components\TextInput::make('employee.division.name')
                            ->label('Division')
                            ->disabled(),
                        Forms\Components\TextInput::make('employee.position.name')
                            ->label('Position')
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
        $data = $this->form->getState();

        $user = $this->getUser();

        $user->update($data);

        $this->data['password'] = null;
        $this->data['password_confirmation'] = null;

        Notification::make()
            ->title('Profile updated successfully.')
            ->success()
            ->send();
    }
}
