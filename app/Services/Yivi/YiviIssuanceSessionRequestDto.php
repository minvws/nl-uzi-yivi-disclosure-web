<?php

declare(strict_types=1);

namespace App\Services\Yivi;

readonly class YiviIssuanceSessionRequestDto extends YiviSessionRequestDto
{
    /**
     * @param string $credential
     * @param string $revocationKey
     * @param int $validity
     * @param array<mixed> $attributes
     */
    public function __construct(
        public string $credential,
        public string $revocationKey,
        public int $validity,
        public array $attributes,
    ) {
        parent::__construct(
            context: YiviSessionRequestContextEnum::ISSUANCE_REQUEST,
            request: [
                'credentials' => [[
                    'credential' => $this->credential,
                    'revocationKey' => $this->revocationKey,
                    'validity' => $this->validity,
                    'attributes' => $this->attributes,
                ]]
            ]
        );
    }
}
