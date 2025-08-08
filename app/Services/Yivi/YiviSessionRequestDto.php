<?php

declare(strict_types=1);

namespace App\Services\Yivi;

use JsonException;

readonly class YiviSessionRequestDto
{
    /**
     * @param YiviSessionRequestContextEnum $context
     * @param array<mixed> $request
     */
    public function __construct(
        public YiviSessionRequestContextEnum $context,
        public array $request = [],
    ) {
    }

    /**
     * Convert the DTO to an array representation suitable for JSON serialization.
     *
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            '@context' => $this->context->value,
            ...$this->request,
        ];
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Convert the DTO to the payload for the signed JWT.
     *
     * This method prepares the payload according to the Yivi documentation for signed session requests.
     * Documentation: https://docs.yivi.app/session-requests/#jwts-signed-session-requests
     *
     * @return array<mixed>
     */
    public function toSignedPayloadArray(): array
    {
        return [
            'sub' => YiviSessionRequestContextEnum::mapToSubject($this->context),
            YiviSessionRequestContextEnum::mapToRequestFieldName($this->context) => [
                'request' => $this->toArray(),
            ],
        ];
    }
}
