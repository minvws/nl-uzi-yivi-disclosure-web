<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\IrmaController;
use Illuminate\Support\ServiceProvider;

class UziServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(
            IrmaController::class,
            function () {
                return new IrmaController(config('uzi.internal_irma_url'), config('uzi.irma_disclosure_prefix'));
            }
        );
    }
}
