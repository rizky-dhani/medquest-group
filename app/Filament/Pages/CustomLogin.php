<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Support\Enums\Width;

class CustomLogin extends BaseLogin
{
    public function getMaxContentWidth(): Width | string | null
    {
        return Width::ExtraSmall;
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
