<?php

namespace App\Http\Middleware;
use App\Models\UziUser;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateUzi
{
    public function handle(Request $request, Closure $next)
    {
        $uziUserSerialized = $request->session()->get('uzi', null);
        if($uziUserSerialized !== null){
            $uziUser = UziUser::deserializeFromJson($uziUserSerialized);
            if($uziUser !== null){
                Auth::setUser($uziUser);
                return $next($request);
            }
        }
        return redirect(RouteServiceProvider::LOGIN);
    }
}
