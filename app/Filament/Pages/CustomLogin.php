<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    protected ?string $heading = null;

    public function getHeading(): string
    {
        return '';
    }

    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => '',
            'password' => '',
            'remember' => true,
        ]);
    }
}
