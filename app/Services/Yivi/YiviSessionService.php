<?php

declare(strict_types=1);

namespace App\Services\Yivi;

use App\Models\UziUser;
use App\Models\UziRelation;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class YiviSessionService
{
    public function __construct(
        protected string $internalYiviServerUrl,
        protected bool|string $internalYiviServerVerifyTls,
        protected string $yiviDisclosurePrefix,
        protected int $yiviValidityPeriodInWeeks,
    ) {
    }

    protected function getSessionUrl(): string
    {
        return rtrim($this->internalYiviServerUrl, '/') . '/session';
    }

    /**
     * Start a Yivi session by sending a POST request to the Yivi server.
     *
     * @param YiviSessionBodyDto $body
     * @return array<mixed>
     * @throws RequestException|ConnectionException
     */
    public function startSession(YiviSessionBodyDto $body): array
    {
        return Http::withOptions([
                'verify' => $this->internalYiviServerVerifyTls,
            ])
            ->post($this->getSessionUrl(), $body->toArray())
            ->throw()
            ->json();
    }

    /**
     * Build the DTO for a Yivi session request.
     *
     * @param UziUser $user
     * @param UziRelation $ura
     * @return YiviSessionBodyDto
     */
    public function buildSessionBody(UziUser $user, UziRelation $ura): YiviSessionBodyDto
    {
        return new YiviSessionBodyDto(
            context: 'https://irma.app/ld/request/issuance/v2',
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
