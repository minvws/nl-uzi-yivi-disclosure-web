<?php

declare(strict_types=1);

namespace Tests\Feature\Services\Yivi;

use App\Services\Yivi\YiviSessionService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class YiviSessionServiceTest extends TestCase
{
    public function testStartSessionSendsHttpRequestAndReturnsResponse(): void
    {
        $service = $this->getService();
        $user = $this->getMockUziUserWithRelations();
        $ura = $user->uras[0];

        $dto = $service->buildSessionBody($user, $ura);

        $expectedResponse = ['sessionPtr' => ['foo' => 'bar']];
        Http::fake([
            'https://yivi-server.local/session' => Http::response($expectedResponse, 200),
        ]);
        $result = $service->startSession($dto);

        $this->assertEquals($expectedResponse, $result);

        Http::assertSent(static function (Request $request) use ($dto) {
            return $request->url() === 'https://yivi-server.local/session'
                && $request['@context'] === $dto->context;
        });
    }
    protected function getService(): YiviSessionService
    {
        return new YiviSessionService(
            internalYiviServerUrl: 'https://yivi-server.local',
            internalYiviServerVerifyTls: true,
            yiviDisclosurePrefix: 'yivi-disclosure-',
            yiviValidityPeriodInWeeks: 9,
        );
    }
}
