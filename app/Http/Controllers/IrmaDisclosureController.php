<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class IrmaDisclosureController
{
    public function disclosures(): Factory|View|Application
    {
        return view('disclosure');
    }

    public function disclose(Request $request)
    {
        $requestUraNumber = $request->get("ura");
        $user = Auth::user();
        $ura = array_filter($user->uras, function($currentUra) use ($requestUraNumber){
            return $currentUra->ura == $requestUraNumber;
        })[0];
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
        $resp = Http::post("http://localhost:8088/session", $body)
            ->throw()
            ->json();
        return view('disclose');
    }
}