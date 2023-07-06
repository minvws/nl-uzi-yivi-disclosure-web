<?php

declare(strict_types=1);

return [
    'internal_server_url' => env('YIVI_INTERNAL_SERVER_URL'),
    'internal_server_verify_tls' => env('YIVI_INTERNAL_SERVER_VERIFY_TLS', true),
    'disclosure_prefix' => env('YIVI_DISCLOSURE_PREFIX'),
    'validity_period_in_weeks' => (int)env('YIVI_VALIDITY_PERIOD_IN_WEEKS', 9),
];
