<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Uzi\UziAuthGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootAuth();
    }

    public function bootAuth(): void
    {
        Auth::extend('oidc', function ($app, $name, array $config) {
            return new UziAuthGuard($app->make('session')->driver());
        });
    }
}
