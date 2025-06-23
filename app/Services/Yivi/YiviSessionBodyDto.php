<?php

declare(strict_types=1);

namespace App\Services\Yivi;

readonly class YiviSessionBodyDto
{
    /**
     * @param string $context
     * @param string $credential
     * @param string $revocationKey
     * @param int $validity
     * @param array<mixed> $attributes
     */
    public function __construct(
        public string $context,
        public string $credential,
        public string $revocationKey,
        public int $validity,
        public array $attributes,
    ) {
    }

    /**
     * Convert the DTO to an array representation suitable for JSON serialization.
     *
     * @return array{
     *     "@context": string,
     *     credentials: array<array{
     *         credential: string,
     *         revocationKey: string,
     *         validity: int,
     *         attributes: array<mixed>
     *     }>
     * }
     */
    public function toArray(): array
    {
        return [
            '@context' => $this->context,
            'credentials' => [[
                'credential' => $this->credential,
                'revocationKey' => $this->revocationKey,
                'validity' => $this->validity,
                'attributes' => $this->attributes,
            ]],
        ];
    }
}
