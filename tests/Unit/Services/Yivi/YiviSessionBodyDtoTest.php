<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Yivi;

use App\Services\Yivi\YiviIssuanceSessionRequestDto;
use PHPUnit\Framework\TestCase;

class YiviSessionBodyDtoTest extends TestCase
{
    public function testToArrayReturnsCorrectStructure(): void
    {
        $dto = new YiviIssuanceSessionRequestDto(
            credential: 'yivi-disclosure-',
            revocationKey: 'uziId-123-ura-456',
            validity: 1234567890,
            attributes: [
                'initials' => 'A',
                'surnamePrefix' => 'van',
                'surname' => 'Test',
                'entityName' => 'TestEntity',
                'ura' => '456',
                'uziId' => '123',
                'roles' => 'ROLE1, ROLE2',
                'loaAuthn' => 'high',
                'loaUzi' => 'medium',
            ]
        );
        $arr = $dto->toArray();

        $this->assertEquals([
            '@context' => 'https://irma.app/ld/request/issuance/v2',
            'credentials' => [
                [
                    'credential' => 'yivi-disclosure-',
                    'revocationKey' => 'uziId-123-ura-456',
                    'validity' => 1234567890,
                    'attributes' => [
                        'initials' => 'A',
                        'surnamePrefix' => 'van',
                        'surname' => 'Test',
                        'entityName' => 'TestEntity',
                        'ura' => '456',
                        'uziId' => '123',
                        'roles' => 'ROLE1, ROLE2',
                        'loaAuthn' => 'high',
                        'loaUzi' => 'medium',
                    ],
                ],
            ],
        ], $arr);

        $this->assertJsonStringEqualsJsonString(json_encode($dto->toArray(), JSON_THROW_ON_ERROR), $dto->toJson());
    }
}
