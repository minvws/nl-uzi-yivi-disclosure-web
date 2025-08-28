<?php

declare(strict_types=1);

namespace Tests\Feature\Services\Yivi;

use App\Services\Yivi\YiviSessionService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Tests\TestCase;

class YiviSessionServiceTest extends TestCase
{
    public function testStartSessionSendsHttpRequestAndReturnsResponse(): void
    {
        // Mock the HTTP client and set the expected response of the Yivi server
        $expectedResponseFromYiviServer = ['sessionPtr' => ['foo' => 'bar']];
        Http::fake([
            'https://yivi-server.local/session' => Http::response($expectedResponseFromYiviServer, 200, [
                'Content-Type' => 'application/json',
            ]),
        ]);

        // Initialize the YiviSessionService to be tested
        $service = new YiviSessionService(
            internalYiviServerUrl: 'https://yivi-server.local',
            internalYiviServerVerifyTls: true,
            yiviDisclosurePrefix: 'yivi-disclosure-',
            yiviValidityPeriodInWeeks: 9,
            authenticationEnabled: false,
        );

        // Get the issuance session body DTO based on a mock user and URA
        $user = $this->getMockUziUserWithRelations();
        $dto = $service->buildIssuanceSessionBody($user, $user->uras[0]);

        // Start testing the session start
        $result = $service->startSession($dto);

        // Assert that the result matches the faked response
        $this->assertEquals($expectedResponseFromYiviServer, $result);

        // Assert that the HTTP request was sent with the expected URL and body
        Http::assertSent(static function (Request $request) use ($dto) {
            // Check if the request body matches the json return by dto
            $bodyIsEqual = $request->body() === $dto->toJson();
            $contentTypeIsJson = $request->isJson();

            return $request->url() === 'https://yivi-server.local/session'
                && $bodyIsEqual
                && $contentTypeIsJson;
        });
    }

    public function testStartSessionSendsSignedHttpRequestAndReturnsResponse(): void
    {
        // Mock the HTTP client and set the expected response of the Yivi server
        $expectedResponseFromYiviServer = ['sessionPtr' => ['foo' => 'bar']];
        Http::fake([
            'https://yivi-server.local/session' => Http::response($expectedResponseFromYiviServer, 200, [
                'Content-Type' => 'application/json',
            ]),
        ]);

        // Set the expected issuer
        $expectedIssuer = 'test-issuer';

        // Create a JWK for signing the JWT
        $privateJWK = $this->generateTestingRSAKey();
        $publicJWK = $privateJWK->toPublic();

        // Initialize the YiviSessionService to be tested
        $service = new YiviSessionService(
            internalYiviServerUrl: 'https://yivi-server.local',
            internalYiviServerVerifyTls: true,
            yiviDisclosurePrefix: 'yivi-disclosure-',
            yiviValidityPeriodInWeeks: 9,
            authenticationEnabled: true,
            authenticationJwtIssuer: $expectedIssuer,
            authenticationJwtPrivateKey: $privateJWK
        );

        // Get the issuance session body DTO based on a mock user and URA
        $user = $this->getMockUziUserWithRelations();
        $dto = $service->buildIssuanceSessionBody($user, $user->uras[0]);

        // Start testing the session start
        $result = $service->startSession($dto);

        // Assert that the result matches the faked response
        $this->assertEquals($expectedResponseFromYiviServer, $result);

        // Assert that the HTTP request was sent with the expected URL and body
        Http::assertSent(function (Request $request) use ($expectedIssuer, $dto, $publicJWK) {
            if ($request->url() !== 'https://yivi-server.local/session') {
                return false;
            }

            // Check if the request body matches the expected signed data
            $this->assertTrue($request->hasHeader('Content-Type', 'text/plain'));

            // Check if the payload of the jwt contains at least the issuer, issued at and subject
            $body = $request->body();
            $explodedJwt = explode('.', $body);
            $decodedPayload = json_decode(base64_decode($explodedJwt[1]), true, 512, JSON_THROW_ON_ERROR);

            $this->assertNotEmpty($decodedPayload['iat']);
            $this->assertEquals('issue_request', $decodedPayload['sub']);
            $this->assertEquals($expectedIssuer, $decodedPayload['iss']);
            $this->assertEquals($dto->toArray(), $decodedPayload['iprequest']['request']);

            // Check if the JWT is signed with the expected key
            $this->assertTrue($this->jwsSignatureMatchesPublicKey($body, $publicJWK));

            return true;
        });
    }

    /**
     * Generate an insecure JWK for testing purposes.
     *
     * @return JWK
     */
    protected function generateTestingRSAKey(): JWK
    {
        return JWKFactory::createRSAKey(
            512, // Warning, using a small key size for testing purposes only
        );
    }

    protected function jwsSignatureMatchesPublicKey(string $jws, JWK $publicKey): bool
    {
        $serializerManager = new JWSSerializerManager([new CompactSerializer()]);
        $verifier = new JWSVerifier(new AlgorithmManager([new RS256()]));

        $jws = $serializerManager->unserialize($jws);

        return $verifier->verifyWithKey($jws, $publicKey, 0);
    }
}
