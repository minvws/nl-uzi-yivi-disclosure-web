<?php

declare(strict_types=1);

namespace App\Services\Uzi;

use App\Models\UziUser;

interface UziRequestUserInfoInterface
{
    public function requestUserInfo(string $accessToken): UziUser;
}
