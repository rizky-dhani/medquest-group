<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    protected function getRedirectUrl(): string
    {
        return filament()->getUrl();
    }
}
