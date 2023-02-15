<?php

declare(strict_types=1);

namespace App\Services\Uzi;

use Illuminate\Support\Facades\Session;
use Jumbojett\OpenIDConnectClientException;

class OpenIDConnectClient extends \Jumbojett\OpenIDConnectClient
{
    /**
     * @throws OpenIDConnectClientException
     */
    public function getUserinfoEndpoint(): string
    {
        return $this->getProviderConfigValue("userinfo_endpoint");
    }

    /**
     * @throws OpenIDConnectClientException
     */
    public function getIssuer(): string
    {
        return $this->getProviderConfigValue("issuer");
    }

    /**
     * @throws OpenIDConnectClientException
     */
    public function getJwksUri(): string
    {
        return $this->getProviderConfigValue("jwks_uri");
    }


    protected function startSession(): void
    {
        // Laravel magic in the background :)
    }

    protected function commitSession(): void
    {
        Session::save();
    }

    /**
     * @param string $key
     */
    protected function getSessionKey($key): mixed
    {
        if (!Session::has($key)) {
            return false;
        }

        return Session::get($key);
    }

    /**
     * @param string $key
     * @param mixed $value mixed
     */
    protected function setSessionKey($key, $value): void
    {
        Session::put($key, $value);
    }

    /**
     * @param string $key
     */
    protected function unsetSessionKey($key): void
    {
        Session::remove($key);
    }
}
