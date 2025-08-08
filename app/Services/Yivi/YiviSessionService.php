<?php

declare(strict_types=1);

namespace App\Services\Yivi;

use App\Attributes\JWKFromConfig;
use App\Models\UziUser;
use App\Models\UziRelation;
use Illuminate\Container\Attributes\Config;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use JsonException;
use LogicException;

#[Singleton]
class YiviSessionService
{
    public function __construct(
        #[Config('yivi.internal_server_url')]
        protected string $internalYiviServerUrl,
        #[Config('yivi.internal_server_verify_tls')]
        protected bool|string $internalYiviServerVerifyTls,
        #[Config('yivi.disclosure_prefix')]
        protected string $yiviDisclosurePrefix,
        #[Config('yivi.validity_period_in_weeks')]
        protected int $yiviValidityPeriodInWeeks,
        #[Config('yivi.authentication.enabled')]
        protected bool $authenticationEnabled,
        #[Config('yivi.authentication.jwt_issuer')]
        protected string $authenticationJwtIssuer = '',
        #[JWKFromConfig('yivi.authentication_private_key_path')]
        protected ?JWK $authenticationJwtPrivateKey = null,
    ) {
        if ($this->authenticationEnabled && !$this->authenticationJwtPrivateKey) {
            throw new InvalidArgumentException('Authentication is enabled but no private key is provided.');
        }
    }

    protected function getSessionUrl(): string
    {
        return rtrim($this->internalYiviServerUrl, '/') . '/session';
    }

    /**
     * Start a Yivi session by sending a POST request to the Yivi server.
     *
     * @param YiviSessionRequestDto $body
     * @return array<mixed>
     * @throws JsonException|RequestException|ConnectionException
     */
    public function startSession(YiviSessionRequestDto $body): array
    {
        if ($this->authenticationEnabled) {
            $payload = $this->signSessionRequest($body);
            $contentType = 'application/jose';
        } else {
            $payload = $body->toJson();
            $contentType = 'application/json';
        }

        return Http::withOptions([
                'verify' => $this->internalYiviServerVerifyTls,
            ])
            ->withBody($payload, $contentType)
            ->post($this->getSessionUrl())
            ->throw()
            ->json();
    }

    /**
     * Sign the session body as a JWT using the private key.
     *
     * @param YiviSessionRequestDto $body
     * @return string
     * @throws JsonException
     */
    protected function signSessionRequest(YiviSessionRequestDto $body): string
    {
        if (!$this->authenticationJwtPrivateKey) {
            throw new LogicException('No authentication private key provided for signing JWT.');
        }

        $jwtPayload = [
            'iss' => $this->authenticationJwtIssuer,
            'iat' => time(),
            ...$body->toSignedPayloadArray(),
        ];

        $jwtBuilder = new JWSBuilder(
            new AlgorithmManager([
                new RS256()
            ])
        );
        $jws = $jwtBuilder->create()
            ->withPayload(json_encode($jwtPayload, JSON_THROW_ON_ERROR))
            ->addSignature($this->authenticationJwtPrivateKey, ['alg' => 'RS256'])
            ->build();

        return (new CompactSerializer())->serialize($jws, 0);
    }

    /**
     * Build the DTO for a Yivi session request.
     *
     * @param UziUser $user
     * @param UziRelation $ura
     * @return YiviIssuanceSessionRequestDto
     */
    public function buildIssuanceSessionBody(UziUser $user, UziRelation $ura): YiviIssuanceSessionRequestDto
    {
        return new YiviIssuanceSessionRequestDto(
            credential: $this->yiviDisclosurePrefix,
            revocationKey: 'uziId-' . $user->uziId . '-ura-' . $ura->ura,
            validity: time() + $this->yiviValidityPeriodInWeeks * 7 * 24 * 60 * 60,
            attributes: [
                'initials' => $user->initials,
                'surnamePrefix' => $user->surnamePrefix,
                'surname' => $user->surname,
                'entityName' => $ura->entityName,
                'ura' => $ura->ura,
                'uziId' => $user->uziId,
                'roles' => implode(', ', $ura->getRoleCodes()),
                'loaAuthn' => $user->loaAuthn,
                'loaUzi' => $user->loaUzi
            ]
        );
    }
}
