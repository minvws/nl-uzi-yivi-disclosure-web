<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Models\UziUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseInterface
{
    public function __construct(
        protected object $userInfo
    ) {
    }

    /**
     * Log the user in and redirect to home page..
     *
     * @param Request $request
     * @return Response
     */
    public function toResponse($request): Response
    {
        $user = UziUser::deserializeFromObject($this->userInfo);
        if ($user === null) {
            throw new AuthorizationException("Empty userinfo");
        }

        Auth::setUser($user);
        return new RedirectResponse(RouteServiceProvider::HOME);
    }
}
