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

    public function getSubject(): string
    {
        return YiviSessionRequestContextEnum::mapToSubject($this->context);
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
}
