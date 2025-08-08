<?php

declare(strict_types=1);

namespace App\Services\Yivi;

enum YiviSessionRequestContextEnum: string
{
    case ISSUANCE_REQUEST = 'https://irma.app/ld/request/issuance/v2';

    /**
     * Maps the context to the specific subject value used in the signed JWT.
     *
     * Documentation: https://docs.yivi.app/session-requests/#jwts-signed-session-requests
     *
     * @param self $context
     * @return string
     */
    public static function mapToSubject(self $context): string
    {
        return match ($context) {
            self::ISSUANCE_REQUEST => 'issue_request',
        };
    }

    /**
     * Maps the context to the specific request field name used in the signed JWT.
     *
     * Documentation: https://docs.yivi.app/session-requests/#jwts-signed-session-requests
     *
     * @param self $context
     * @return string
     */
    public static function mapToRequestFieldName(self $context): string
    {
        return match ($context) {
            self::ISSUANCE_REQUEST => 'iprequest',
        };
    }
}
