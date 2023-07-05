<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request                                       $request
     * @param  \Closure(\Illuminate\Http\Request):
     *          (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @param  string|null                                                    ...$guards
     * @return Redirector|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next, ...$guards): Redirector|RedirectResponse|Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect()->route('yivi-disclosure');
            }
        }

        return $next($request);
    }
}
