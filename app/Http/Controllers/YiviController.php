<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\YiviStartRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\Yivi\YiviSessionService;

class YiviController extends Controller
{
    public function __construct(
        protected YiviSessionService $yiviSessionService,
    ) {
    }

    public function disclosures(Request $request): View
    {
        /* @var \App\Models\UziUser $user */
        $user = $request->user();

        return view('disclosure', [
            'userUras' => $user->uras,
        ]);
    }

    /**
     * The endpoint is used by JavaScript to initiate the Yivi credential issuance process.
     *
     * @param YiviStartRequest $request
     * @return JsonResponse
     * @throws RequestException
     * @throws ConnectionException
     */
    public function start(YiviStartRequest $request): JsonResponse
    {
        /* @var \App\Models\UziUser $user */
        $user = $request->user();
        $ura = $request->getValidatedUra();

        $body = $this->yiviSessionService->buildIssuanceSessionBody($user, $ura);

        $response = $this->yiviSessionService->startSession($body);
        return response()
            ->json(["sessionPtr" => $response["sessionPtr"], "frontendRequest" => $response["frontendRequest"]]);
    }
}
