<?php

declare(strict_types=1);

namespace App\Services\Uzi;

use App\Models\UziUser;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jose\Component\Core\JWKSet;
use Jose\Easy\Load;
use Jose\Easy\Validate;

class UziRequestUserInfoService implements UziRequestUserInfoInterface
{
    protected OpenIDConfiguration $openIDConfiguration;

    public function __construct(
            protected string $issuer,
        protected UziJweDecryptInterface $jweDecryptService,
    ) {
        $this->openIDConfiguration = $this->getOpenIDConfiguration();
    }

    /**
     * @throws Exception
     */
    private function getOpenIDConfiguration(): OpenIDConfiguration
    {
        try{
            $response = Http::withOptions(["verify"=>false])->get($this->issuer . '/.well-known/openid-configuration');
        } catch(Exception $e){
            Log::error('Exception thrown while connecting to oidc server');
            throw $e;
        }
        if($response->status()>=400){
            throw new Exception("Unsuccessful status returned for openid-configuration");
        }
        return new OpenIDConfiguration(
                version: $response->json('version'),
            tokenEndpointAuthMethodsSupported: $response->json('token_endpoint_auth_methods_supported'),
            claimsParameterSupported: $response->json('claims_parameter_supported'),
            requestParameterSupported: $response->json('request_parameter_supported'),
            requestUriParameterSupported: $response->json('request_uri_parameter_supported'),
            requireRequestUriRegistration: $response->json('require_request_uri_registration'),
            grantTypesSupported: $response->json('grant_types_supported'),
            frontchannelLogoutSupported: $response->json('frontchannel_logout_supported'),
            frontchannelLogoutSessionSupported: $response->json('frontchannel_logout_session_supported'),
            backchannelLogoutSupported: $response->json('backchannel_logout_supported'),
            backchannelLogoutSessionSupported: $response->json('backchannel_logout_session_supported'),
            issuer: $response->json('issuer'),
            authorizationEndpoint: $response->json('authorization_endpoint'),
            jwksUri: $response->json('jwks_uri'),
            tokenEndpoint: $response->json('token_endpoint'),
            scopesSupported: $response->json('scopes_supported'),
            responseTypesSupported: $response->json('response_types_supported'),
            responseModesSupported: $response->json('response_modes_supported'),
            subjectTypesSupported: $response->json('subject_types_supported'),
            idTokenSigningAlgValuesSupported: $response->json('id_token_signing_alg_values_supported'),
            userinfoEndpoint: $response->json('userinfo_endpoint'),
            codeChallengeMethodsSupported: $response->json('code_challenge_methods_supported')
        );
    }

    /**
     * @throws Exception
     */
    public function requestUserInfo(string $accessToken): UziUser
    {
        // Get user info endpoint
        $jwe = Http::withToken($accessToken)
            ->get($this->openIDConfiguration->userinfoEndpoint . '?schema=openid')
            ->throw()
            ->body();

        // Decrypt jwe to jwt
        $jwt = $this->jweDecryptService->decrypt($jwe);

        // Verify JWT
        /** @var Validate $jws */
        $jws = Load::jws($jwt)
            ->algs(['RS256'])
            ->exp()
            ->iss($this->issuer)
            ->keyset($this->getJwkSet());

        $jwt = $jws->run();

        return UziUser::getFromParameterBag($jwt->claims);
    }

    private function getJwkSet(): JWKSet
    {
        $response = Http::get($this->openIDConfiguration->jwksUri)->body();

        return JWKSet::createFromJson($response);
    }
}
