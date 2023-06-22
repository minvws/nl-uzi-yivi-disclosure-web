<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use Illuminate\Support\ServiceProvider;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseInterface;

class OidcServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LoginResponseInterface::class, LoginResponse::class);
    }
}
