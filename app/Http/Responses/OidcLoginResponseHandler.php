<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Exceptions\UziNoUziNumberException;
use App\Models\UziUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class OidcLoginResponseHandler implements LoginResponseHandlerInterface
{
    /**
     * @throws UziNoUziNumberException
     */
    public function handleLoginResponse(object $userInfo): Response
    {
        $user = UziUser::deserializeFromObject($userInfo);
        if ($user === null) {
            return redirect()
                ->route('login')
                ->with('error', __('Something went wrong with logging in, please try again.'));
        }

        if (!$user->hasUziId()) {
            throw new UziNoUziNumberException();
        }

        Auth::setUser($user);
        return new RedirectResponse(route('yivi-disclosure'));
    }
}
