<?php

declare(strict_types=1);

return [
    // True if the CA must be checked on the x509 certificate
    'strict_ca_check' => env('UZI_STRICT_CA_CHECK', true),
    'oidc_client' => [
            'issuer' => env('UZI_OIDC_CLIENT_ISSUER', ''),
            'id' => env('UZI_OIDC_CLIENT_ID', ''),
            'decryption_key_path' => env('UZI_OIDC_CLIENT_KEY_PATH', ''),
    ],
    'internal_irma_url' => env('INTERNAL_IRMA_URL'),
    'irma_disclosure_prefix' => env('IRMA_DISCLOSURE_PREFIX')
];
