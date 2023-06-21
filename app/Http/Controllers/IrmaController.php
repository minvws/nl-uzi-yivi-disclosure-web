<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IrmaStartRequest;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class IrmaController
{
    public function __construct(
        protected string $internalIrmaUrl,
        protected string $irmaDisclosurePrefix,
    ) {
    }

    public function disclosures(): Factory|View|Application
    {
        return view('disclosure');
    }

    public function start(IrmaStartRequest $request): JsonResponse
    {
        /* @var UziUser $user */
        $user = $request->user();

        $ura = $request->getValidatedUra();
        $body = [
                "@context" => "https://irma.app/ld/request/issuance/v2",
                "credentials" => [[
                        "credential" => $this->irmaDisclosurePrefix,
                        "revocationKey" => "uziId-" . $user->uziId . "-ura-" . $ura->ura,
                        "attributes" => [
                            "initials" => $user->initials,
                            "surnamePrefix" => $user->surnamePrefix,
                            "surname" => $user->surname,
                            "entityName" => $ura->entityName,
                            "ura" => $ura->ura,
                            "uziId" => $user->uziId,
                            "roles" => implode(", ", $ura->roles),
                            "loaAuthn" => $user->loaAuthn,
                            "loaUzi" => $user->loaUzi
                        ]
                    ]
                ]
        ];

        $resp = Http::post($this->internalIrmaUrl . "/session", $body)
            ->throw()
            ->json();
        return response()
            ->json(["sessionPtr" => $resp["sessionPtr"]]);
    }
}
