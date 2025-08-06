<?php

declare(strict_types=1);

namespace App\Services\Yivi;

enum YiviSessionRequestContextEnum: string
{
    case ISSUANCE_REQUEST = 'https://irma.app/ld/request/issuance/v2';

    public static function mapToSubject(self $context): string
    {
        return match ($context) {
            self::ISSUANCE_REQUEST => 'issue_request',
        };
    }
}
