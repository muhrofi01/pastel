<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login;

class SSOLogin extends Login
{
    protected static string $view = 'filament.pages.sso-login';

    public function getHeading(): string
    {
        return ''; // menghilangkan tulisan "Sign in"
    }
}