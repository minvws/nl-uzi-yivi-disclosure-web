<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\YiviController;
use App\Http\Requests\YiviStartRequest;
use App\Services\Yivi\YiviIssuanceSessionRequestDto;
use App\Services\Yivi\YiviSessionService;
use Mockery;
use Tests\TestCase;

class YiviControllerTest extends TestCase
{
    public function testStartReturnsSessionPtrJson(): void
    {
        // Arrange
        $user = $this->getMockUziUserWithRelations();
        $ura = $user->uras[0];

        // Set expectations that the controller will call the service to build the session body
        $request = Mockery::mock(YiviStartRequest::class);
        $request->shouldReceive('user')->andReturn($user);
        $request->shouldReceive('getValidatedUra')->andReturn($ura);

        $exampleDto = $this->getExampleYiviSessionBodyDto();

        $expectedSessionPtr = 'example-session-ptr';

        $service = Mockery::mock(YiviSessionService::class);
        $service->shouldReceive('buildIssuanceSessionBody')->with($user, $ura)->andReturn($exampleDto);
        $service->shouldReceive('startSession')->with($exampleDto)->andReturn([
            'sessionPtr' => $expectedSessionPtr,
            'somethingElse' => 'irrelevant data',
        ]);

        $controller = new YiviController($service);

        // Act
        $response = $controller->start($request);

        // Assert
        $this->assertEquals(['sessionPtr' => $expectedSessionPtr], $response->getData(true));
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function getExampleYiviSessionBodyDto(): YiviIssuanceSessionRequestDto
    {
        return new YiviIssuanceSessionRequestDto(
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
    }
}
