<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IrmaController
{
    public function __construct(
        protected string $internalIrmaUrl
    ) {
    }

    public function disclosures(): Factory|View|Application
    {
        return view('disclosure');
    }

    public function start(Request $request)
    {
        $requestUraNumber = $request->get("ura");
        $user = Auth::user();
        $uras = array_filter(
            $user->uras,
            function ($currentUra) use ($requestUraNumber) {
                return $currentUra->ura == $requestUraNumber;
            }
        );
        $ura = reset($uras);
        $body = [
                "@context" => "https://irma.app/ld/request/issuance/v2",
                "credentials" => [[
                        "credential" => "irma-demo.uzipoc-cibg.uzi-2",
                        "attributes" => [
                            "initials" => $user->initials,
                            "surnamePrefix" => $user->surnamePrefix,
                            "surname" => $user->surname,
                            "entityName" => $ura->entityName,
                            "ura" => $ura->ura,
                            "uziId" => $user->uziId,
                            "roles" => join(", ", $ura->roles),
                            "loaAuthn" => $user->loaAuthn,
                            "loaUzi" => $user->loaUzi
                        ]
                    ]
                ]
        ];
        $resp = Http::post($this->internalIrmaUrl . "/session", $body)
            ->throw()
            ->json();
        return ["sessionPtr" => $resp["sessionPtr"]];
    }
}
