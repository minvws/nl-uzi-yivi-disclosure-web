<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\YiviStartRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class YiviController extends Controller
{
    protected string $internalYiviServerSessionUrl = '';

    public function __construct(
        protected string $internalYiviServerUrl,
        protected bool $internalYiviServerVerifyTls,
        protected string $yiviDisclosurePrefix,
        protected int $yiviValidityPeriodInWeeks,
    ) {
        $this->internalYiviServerSessionUrl = $this->internalYiviServerUrl . "/session";
    }

    public function disclosures(Request $request): View
    {
        /* @var \App\Models\UziUser $user */
        $user = $request->user();

        return view('disclosure', [
            'userUras' => $user->uras,
        ]);
    }

    public function start(YiviStartRequest $request): JsonResponse
    {
        /* @var \App\Models\UziUser $user */
        $user = $request->user();

        $ura = $request->getValidatedUra();
        $body = [
                "@context" => "https://irma.app/ld/request/issuance/v2",
                "credentials" => [[
                        "credential" => $this->yiviDisclosurePrefix,
                        "revocationKey" => "uziId-" . $user->uziId . "-ura-" . $ura->ura,
                        "validity" => time() + $this->yiviValidityPeriodInWeeks * 7 * 24 * 60 * 60,
                        "attributes" => [
                            "initials" => $user->initials,
                            "surnamePrefix" => $user->surnamePrefix,
                            "surname" => $user->surname,
                            "entityName" => $ura->entityName,
                            "ura" => $ura->ura,
                            "uziId" => $user->uziId,
                            "roles" => implode(", ", $ura->getRoleCodes()),
                            "loaAuthn" => $user->loaAuthn,
                            "loaUzi" => $user->loaUzi
                        ]
                    ]
                ]
        ];

        $response = $this->startYiviSession($body);
        return response()
            ->json(["sessionPtr" => $response["sessionPtr"]]);
    }

    /**
     * @param array<mixed> $body
     * @return array<mixed>
     * @throws RequestException
     */
    protected function startYiviSession(array $body): array
    {
        if ($this->internalYiviServerVerifyTls === false) {
            return Http::withoutVerifying()
                ->post($this->internalYiviServerSessionUrl, $body)
                ->throw()
                ->json();
        }

        return Http::post($this->internalYiviServerSessionUrl, $body)
            ->throw()
            ->json();
    }
}
