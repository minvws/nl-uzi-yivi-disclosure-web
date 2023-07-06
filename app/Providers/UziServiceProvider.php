<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\YiviController;
use Illuminate\Support\ServiceProvider;

class UziServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(
            YiviController::class,
            function () {
                return new YiviController(
                    internalYiviServerUrl: config('yivi.internal_server_url'),
                    internalYiviServerVerifyTls: config('yivi.internal_server_verify_tls'),
                    yiviDisclosurePrefix: config('yivi.disclosure_prefix'),
                    yiviValidityPeriodInWeeks: config('yivi.validity_period_in_weeks')
                );
            }
        );
    }
}
