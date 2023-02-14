<?php

declare(strict_types=1);

namespace App\Services\Uzi;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Jumbojett\OpenIDConnectClientException;

interface UziInterface
{
    /**
     * @throws OpenIDConnectClientException
     */
    public function authenticate();

    /**
     * @throws OpenIDConnectClientException
     */
    public function login(Request $request): RedirectResponse;
}