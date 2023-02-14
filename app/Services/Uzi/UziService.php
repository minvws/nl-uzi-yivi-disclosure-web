<?php

declare(strict_types=1);

namespace App\Services\Uzi;

use App\Exceptions\UziNoUziNumberException;
use App\Models\UziUser;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Jose\Component\Core\JWKSet;
use Jose\Easy\Load;
use Jose\Easy\Validate;
use Jumbojett\OpenIDConnectClientException;

class UziService implements UziInterface
{
    private OpenIDConnectClient $oidc;

    public function __construct(
            private UziJweDecryptService $uziJweDecryptService
    )
    {
        $this->oidc = new OpenIDConnectClient(provider_url: config('uzi.oidc_client.issuer'));
        #FIXME: This from oidc configuration?
        $this->oidc->setVerifyPeer(false);
        $this->oidc->setVerifyHost(false);

        $this->oidc->setClientID(config('uzi.oidc_client.id'));
        #FIXME: This from oidc configuration?
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
     * @throws UziNoUziNumberException
     */
    public function login(Request $request): RedirectResponse
    {
        $uziResponse = $this->fetchUserInfo();
        if (empty($uziResponse->uziId)) {
            throw new UziNoUziNumberException();
        }

        $request->session()->put('uzi', json_encode($uziResponse));
        return new RedirectResponse(RouteServiceProvider::HOME);
    }

    /**
     * @throws RequestException
     * @throws OpenIDConnectClientException
     * @throws Exception
     */
    private function fetchUserInfo(): UziUser
    {
        // Get user info endpoint
        $jwe = Http::withToken($this->oidc->getAccessToken())
            #FIXME: This from oidc configuration?
            ->withOptions(["verify"=>false])
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
            ->keyset($this->getJwkSet());

        $jwt = $jws->run();

        return UziUser::getFromParameterBag($jwt->claims);
    }

    /**
     * @throws OpenIDConnectClientException
     */
    private function getJwkSet(): JWKSet
    {
        #FIXME: This from oidc configuration?
        $response = Http::withOptions(["verify"=>false])
            ->get($this->oidc->getJwksUri())
            ->body();

        return JWKSet::createFromJson($response);
    }
}