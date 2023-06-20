<?php

declare(strict_types=1);

namespace App\Services\Uzi;

use App\Models\UziUser;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Jose\Component\Core\JWKSet;
use Jose\Easy\Load;
use Jumbojett\OpenIDConnectClientException;

class UziService implements UziInterface
{
    private OpenIDConnectClient $oidc;

    public function __construct(
        private UziJweDecryptService $uziJweDecryptService
    ) {
        $this->oidc = new OpenIDConnectClient(provider_url: config('uzi.oidc_client.issuer'));
        // FIXME: This from oidc configuration?
        $this->oidc->setVerifyPeer(false);
        $this->oidc->setVerifyHost(false);

        $this->oidc->setClientID(config('uzi.oidc_client.id'));
        // FIXME: This from oidc configuration?
        $this->oidc->setCodeChallengeMethod('S256');
        $this->oidc->setRedirectURL(route('uzi.login'));
    }

    /**
     * @throws OpenIDConnectClientException
     */
    public function authenticate(): void
    {
        $this->oidc->authenticate();
    }

    /**
     * @throws OpenIDConnectClientException
     * @throws RequestException
     * @throws AuthorizationException
     */
    public function login(Request $request): RedirectResponse
    {
        $uziUser = $this->fetchUserInfo();
        if ($uziUser === null) {
            throw new AuthorizationException("Empty userinfo");
        }

        Auth::setUser($uziUser);
        return new RedirectResponse(RouteServiceProvider::HOME);
    }

    /**
     * @throws RequestException
     * @throws OpenIDConnectClientException
     * @throws Exception
     */
    private function fetchUserInfo(): UziUser | null
    {
        // Get user info endpoint
        $jwe = Http::withToken($this->oidc->getAccessToken())
            // FIXME: This from oidc configuration?
            ->withOptions(["verify" => false])
            ->get($this->oidc->getUserinfoEndpoint() . '?schema=openid')
            ->throw()
            ->body();

        // Decrypt jwe to jwt
        $jwt = $this->uziJweDecryptService->decrypt($jwe);
        // Verify JWT
        $jws = Load::jws($jwt)
            ->algs(['RS256'])
            ->exp()
            ->iss($this->oidc->getIssuer())
            ->aud($this->oidc->getClientID())
            ->keyset($this->getJwkSet());

        /**
        * @psalm-suppress UndefinedMethod
        */
        $jwt = $jws->run();
        return UziUser::getFromParameterBag($jwt->claims);
    }

    /**
     * @throws OpenIDConnectClientException
     */
    private function getJwkSet(): JWKSet
    {
        // FIXME: This from oidc configuration?
        $response = Http::withOptions(["verify" => false])
            ->get($this->oidc->getJwksUri())
            ->body();

        return JWKSet::createFromJson($response);
    }
}
