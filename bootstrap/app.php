<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustHosts(static function (): array {
            $trustedHosts = Config::array('app.trusted_hosts');

            /**
             * Convert each trusted host to a pattern to match the specific configured host
             * As Symfony Request::setTrustedHosts expects regex patterns.
             */
            return Arr::map($trustedHosts, static function (string $host) {
                return sprintf('^%s$', preg_quote($host, '/'));
            });
        }, subdomains: false);
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\Locale::class,
        ]);
        $middleware->redirectUsersTo(fn (Request $request) => route('yivi-disclosure'));
        $middleware->redirectGuestsTo(fn (Request $request) => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(
            function (Throwable $e) {
                //
            }
        );
    })->create();
