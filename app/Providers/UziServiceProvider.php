<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\IrmaController;
use App\Services\Uzi\UziInterface;
use App\Services\Uzi\UziJweDecryptService;
use App\Services\Uzi\UziService;
use Illuminate\Support\ServiceProvider;

class UziServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(
            UziInterface::class,
            function () {
                return new UziService(
                    uziJweDecryptService: new UziJweDecryptService(
                        decryptionKeyPath: config('uzi.oidc_client.decryption_key_path')
                    )
                );
            }
        );
        $this->app->singleton(
            IrmaController::class,
            function () {
                return new IrmaController(config('uzi.internal_irma_url'), config('uzi.irma_disclosure_prefix'));
            }
        );
    }
}
