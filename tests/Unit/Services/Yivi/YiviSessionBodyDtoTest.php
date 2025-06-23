<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Yivi;

use App\Services\Yivi\YiviSessionBodyDto;
use PHPUnit\Framework\TestCase;

class YiviSessionBodyDtoTest extends TestCase
{
    public function testToArrayReturnsCorrectStructure(): void
    {
        $dto = new YiviSessionBodyDto(
            context: 'https://irma.app/ld/request/issuance/v2',
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
        $this->assertEquals('https://irma.app/ld/request/issuance/v2', $arr['@context']);
        $this->assertEquals('yivi-disclosure-', $arr['credentials'][0]['credential']);
        $this->assertEquals('uziId-123-ura-456', $arr['credentials'][0]['revocationKey']);
        $this->assertEquals(1234567890, $arr['credentials'][0]['validity']);
        $this->assertEquals('A', $arr['credentials'][0]['attributes']['initials']);
        $this->assertEquals('TestEntity', $arr['credentials'][0]['attributes']['entityName']);
        $this->assertEquals('ROLE1, ROLE2', $arr['credentials'][0]['attributes']['roles']);
    }
}
