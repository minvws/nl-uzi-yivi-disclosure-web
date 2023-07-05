<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Factory;

class AuthController
{
    public function login(): View|Factory
    {
        return view('auth.login');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()
            ->route('home');
    }
}
