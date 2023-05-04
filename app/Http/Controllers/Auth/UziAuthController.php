<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Services\Uzi\UziInterface;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UziAuthController
{
    public function __construct(
        protected UziInterface $uziService
    ) {
    }

    public function login(Request $request): RedirectResponse
    {
        $error = $request->query->get("error");
        $error_description = $request->query->get("error_description");
        if ($error != null or $error_description != null) {
            return redirect()
                ->route('login')
                ->with('error', __($error))
                ->with('error_description', $error_description);
        }
        try {
            $this->uziService->authenticate();
            return $this->uziService->login($request);
        } catch (Exception $e) {
            return $this->handleOpenIdClientException($e);
        }
    }

    public function handleOpenIdClientException(Exception $e): RedirectResponse
    {
        switch ($e->getMessage()) {
            // If authentication flow cancelled from uzi pilot
            // If authentication flow cancelled from chosen authentication provider
            case 'User authentication flow failed':
            case 'login_cancelled':
                return redirect()
                ->route('login')
                ->with('error', __('Login cancelled'));
            // If the state incorrect, redirect back to login again.
            case 'Unable to determine state':
                return redirect()
                ->route('login')
                ->with('error', __('Something went wrong with logging in, please try again.'));
            case 'The provider authorization_endpoint could not be fetched.' .
                'Make sure your provider has a well known configuration available.':
                return redirect()
                ->route('login')
                ->with('error', __('Some of the services are currently not working, please try again later.'));
            case 'Empty userinfo':
                return redirect()
                ->route('login')
                ->with('error', __('Unauthorized'));
            default:
                report($e);
                return redirect()
                ->route('login')
                ->with('error', __('Something went wrong. Please refresh your page and try again.'));
        }
    }
}
