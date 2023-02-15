<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Services\Uzi\UziInterface;
use App\Services\Uzi\UziLoginCallbackHandlerInterface;
use App\Services\Uzi\UziRequestUserInfoInterface;
use App\Services\Uzi\OpenIDConnectClient;
use App\Services\Uzi\UziService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jumbojett\OpenIDConnectClientException;

class UziAuthController
{
    public function __construct(
        protected UziInterface $uziService
    )
    {
    }

    public function login(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if($user != null){
            dd($user);
        }

        $error = $request->query->get("error");
        $error_description = $request->query->get("error_description");
        if($error != null or $error_description != null){
            return redirect()
                    ->route('login')
                    ->with('error', __($error))
                    ->with('error_description', $error_description);
        }
        try{
            $this->uziService->authenticate();
            return $this->uziService->login($request);
        } catch(OpenIDConnectClientException $e)
        {
            return $this->handleOpenIdClientException($e);
        }
    }

    public function handleOpenIdClientException(OpenIDConnectClientException $e): RedirectResponse
    {
        switch ($e->getMessage()) {
            // If authentication flow cancelled from uzi pilot
            // If authentication flow cancelled from chosen authentication provider
            case 'User authentication flow failed':
            case 'Error: login_cancelled':
                return redirect()
                    ->route('login')
                    ->with('error', __('Login cancelled'));
            // If the state incorrect, redirect back to login again.
            case 'Unable to determine state':
                return redirect()
                    ->route('login')
                    ->with('error', __('Something went wrong with logging in, please try again.'));
            default:
                report($e);
                abort(500, __('Something went wrong. Please refresh your page and try again.'));
        }
    }
}