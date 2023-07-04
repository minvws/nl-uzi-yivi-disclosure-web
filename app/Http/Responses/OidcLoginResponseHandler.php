<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Models\UziUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class OidcLoginResponseHandler implements LoginResponseHandlerInterface
{
    public function handleLoginResponse(object $userInfo): Response
    {
        $user = UziUser::deserializeFromObject($userInfo);
        if ($user === null) {
            throw new AuthorizationException("Empty userinfo");
        }

        Auth::setUser($user);
        return new RedirectResponse(RouteServiceProvider::HOME);
    }
}
