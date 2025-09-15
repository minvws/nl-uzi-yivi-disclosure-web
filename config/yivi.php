<?php

declare(strict_types=1);

return [
    /**
     * The internal URL of the Yivi server / irmago server.
     * Example: 'https://yivi-server.local'
     *
     * The application can be found at: https://github.com/privacybydesign/irmago
     */
    'internal_server_url' => env('YIVI_INTERNAL_SERVER_URL'),

    /**
     * Whether to verify TLS certificates when connecting to the Yivi server.
     * Boolean: true to verify, false to skip verification (not recommended for production).
     * String: path to a CA bundle file to use for verification.
     *
     * This setting is passed to the Guzzle HTTP client. See the Guzzle documentation for more details:
     * https://docs.guzzlephp.org/en/stable/request-options.html#verify
     */
    'internal_server_verify_tls' => env('YIVI_INTERNAL_SERVER_VERIFY_TLS', true),

    /**
     * Full Credential ID.
     * Example: '"irma-demo.uzipoc-cibg.uzi-acceptance"'
     *
     * This is build based on the `SchemeManager`, `IssuerID` and `CredentialID`.
     * Example: '<SchemeManager>.<IssuerID>.<CredentialID>'
     *
     * The credential should be registered in the scheme manager.
     * The demo scheme manager can be found at: https://github.com/privacybydesign/irma-demo-schememanager
     * The production scheme manager can be found at: https://github.com/privacybydesign/pbdf-schememanager
     */
    'disclosure_prefix' => env('YIVI_DISCLOSURE_PREFIX'),

    /**
     * The validity period (in weeks) for issued credentials.
     * Integer value, default is 9 weeks.
     */
    'validity_period_in_weeks' => (int)env('YIVI_VALIDITY_PERIOD_IN_WEEKS', 9),

    /**
     * Authentication options for Yivi session requests.
     */
    'authentication' => [
        /**
         * Enable or disable authentication (JWT signing) for Yivi session requests.
         *
         * If enabled, the application will sign session requests with a JWT using the private key specified in
         * `jwt_private_key_path`.
         *
         * The documentation can be found at:
         * https://docs.yivi.app/session-requests/#jwts-signed-session-requests
         */
        'enabled' => env('YIVI_AUTHENTICATION_ENABLED', false),

        /**
         * JWT issuer claim ("iss").
         *
         * This should be requestor name, and is used by the Yivi server
         * to look up the appropriate key to verify the JWT.
         */
        'jwt_issuer' => env('YIVI_AUTHENTICATION_JWT_ISSUER', ''),

        /**
         * Path to the private key for signed session requests to the Yivi server.
         */
        'jwt_private_key_path' => env('YIVI_AUTHENTICATION_JWT_PRIVATE_KEY_PATH'),
    ],

];
