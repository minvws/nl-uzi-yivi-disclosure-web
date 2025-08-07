# Yivi Disclosure application
This application handles disclosing [Yivi](https://yivi.app) attributes for UZI users. For additional information about the UZI project, check the coordination repo at https://github.com/minvws/nl-rdo-uzi-coordination.

This repository is part of the project "Toekomstbestendig maken UZI-middelen". For more information about this project see the [Data voor gezondheid website](https://www.datavoorgezondheid.nl) or the [Dezi website](https://www.dezi.nl).

## Running the Application

Requirements:
- PHP 8.3 or higher
- Composer
- Node.js (for frontend dependencies)

**Get up and running:**
The easiest way to get the uzi project running is to run the "make setup" command from the coordination repository. This will automatically run the "make setup" of all project. You also have the option to run "make setup" command manually for each project.

**Manual setup of this project**

The frontend part of this application makes use of the `@minvws/nl-rdo-rijksoverheid-ui-theme` package, which is hosted on GitHub Packages.
To use this package, you need to configure your npm to authenticate with GitHub Packages.
When running the `make setup` command, it will ask for a GitHub Personal Access Token (PAT) with the `read:packages` scope.

For more information about the GitHub Packages, see the [GitHub documentation - Working with the npm registry](https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-npm-registry). 
 
1. Run ```make setup```
3. Run ```make run``` to start the php application for development.

The `php artisan serve` command will start the application on `localhost:8000` by default. This should not be used in production, but is suitable for local development.

**Additional Information:**
When running the project manually you will need:
- an identity provider and configure the OIDC settings in the `.env` file. The application supports OpenID Connect (OIDC) for authentication, and you can use any OIDC-compliant identity provider as long as it provides the necessary userinfo structure, see the [Configuration](#configuration) section below for details.
- a Yivi server (irmaserver). See the [Yivi server documentation](https://github.com/privacybydesign/irmago) for instructions on how to set it up.

**Conclusion:**
If everything is configured the application should be working and should be available on localhost:8000.

## Development

To test the application, you can run the following command to execute the PHPUnit tests:

```bash
vendor/bin/phpunit
```

# Configuration

Configuration files are located in the `config` directory.
The configuration is primarily managed through environment variables, which can be set in a `.env` file at the project root.
See the `.env.example` file for a template of the required environment variables, refer to the `config` directory for all available configuration options.

In development secrets and certificates are stored in the `secrets` directory.

## Trusted Hosts
To ensure security, the application checks the `HTTP_HOST` header against a list of trusted hosts. You can configure the trusted hosts in the `.env` file:

The `TRUSTED_HOSTS` variable should contain a comma-separated list of trusted hostnames or IP addresses. For example:

```plaintext
TRUSTED_HOSTS=localhost
```

## OpenID Connect (OIDC) Integration

This application can connect to an Identity Provider (IdP) using OpenID Connect (OIDC). To enable and configure OIDC authentication, set the relevant environment variables in your `.env` file or system environment:

- `OIDC_ISSUER`: The issuer URL of the OpenID Connect provider.
- `OIDC_CLIENT_ID`: The client ID assigned to your application by the IdP.
- `OIDC_CLIENT_SECRET`: The client secret (if required by the IdP).
- `OIDC_SIGNING_PRIVATE_KEY_PATH`: Path to the private key for `private_key_jwt` authentication (if used).
- `OIDC_DECRYPTION_KEY_PATH`: Path to the private key used for decrypting encrypted ID tokens or userinfo responses (if your IdP sends encrypted responses).

Refer to `config/oidc.php` for all available OIDC configuration options.

### Expected Userinfo Structure

When authenticating via OIDC, the application expects the userinfo response to have the following structure:

Expected fields in the userinfo response:

- `relations`: Array of relation objects. Each object contains:
    - `entity_name` (string): Name of the healthcare provider.
    - `ura` (string): Unique number of the healthcare provider ("UZI-register Abonneenummer").
    - `roles` (string[]): Array of roles assigned to the user within the healthcare provider (for example: "01.000").
- `initials` (string, optional): User's initials.
- `surname` (string, optional): User's surname (last name).
- `surname_prefix` (string, optional): Prefix to the surname (e.g., "van", "de").
- `uzi_id` (string): User's "Unieke Zorgverlener Identificatie" number.
- `loa_uzi` (string): Level of assurance for the UZI credential.
- `loa_authn` (string): Level of assurance for the authentication event.

Ensure your IdP is configured to provide these fields in the userinfo response.

## Yivi Server connection

This application requires a running [Yivi server (irmaserver)](https://github.com/privacybydesign/irmago) for attribute disclosure sessions. You must have access to a Yivi server instance, which can be started by following the instructions in the irmago repository.

Configuration options for connecting to the Yivi server are set in `config/yivi.php` and can be controlled via environment variables:

- `YIVI_INTERNAL_SERVER_URL`: The internal URL of the Yivi server (e.g., `https://yivi-server.local`).
- `YIVI_INTERNAL_SERVER_VERIFY_TLS`: Whether to verify TLS certificates when connecting to the Yivi server (default: true).
- `YIVI_DISCLOSURE_PREFIX`: The full credential ID, built from the scheme manager, issuer ID, and credential ID (e.g., `irma-demo.uzipoc-cibg.uzi-acceptance`).
- `YIVI_VALIDITY_PERIOD_IN_WEEKS`: The validity period (in weeks) for the issued credentials (default: 9 weeks).
- `YIVI_AUTHENTICATION_ENABLED`: Set to `true` to enable JWT signing for Yivi session requests. When enabled, requests will be signed using the configured private key and issuer. Set to `false` to send requests without JWT authentication.
- `YIVI_AUTHENTICATION_JWT_ISSUER`: The value for the JWT `iss` (issuer) claim in signed requests. This must match the requestor name configured on the Yivi server for successful verification.
- `YIVI_AUTHENTICATION_JWT_PRIVATE_KEY_PATH`: Filesystem path to the private key used for signing JWTs. The corresponding public key should be registered on the Yivi server for signature verification. Keep this file secure.

Refer to the comments in `config/yivi.php` for more details on each setting.

Ensure the Yivi server is running and accessible at the configured URL before starting this application.

## High Availability and Scaling

For high availability and horizontal scaling (multiple application servers behind a load balancer), it is important to ensure that user sessions and cache are shared across all servers:

- **Session Driver:** To share user sessions across multiple application servers, configure the session driver to use Redis by setting the following in your `.env` file:
  ```
  SESSION_DRIVER=redis
  ```
- **Cache Store:** For distributed caching, set the cache store to Redis by configuring the following in your `.env` file:
  ```
  CACHE_STORE=redis
  ```
- **OIDC Configuration Cache:** To ensure that OIDC configuration cache is shared across all servers, set the OIDC configuration cache driver to Redis:
  ```
  OIDC_CONFIGURATION_CACHE_DRIVER=redis
  ```

See the `config/database.php` file for all Redis configuration options, including TLS and authentication settings. Make sure your Redis instance is accessible to all application servers and properly secured.
Instead of Redis, you can also use other shared storage solutions like Memcached or a database for session and cache storage.

After making changes to your configuration, it is recommended to clear and re-cache the configuration using the following commands:

  ```bash
  php artisan config:cache
  php artisan cache:clear
  ```

## Technical Environment overview
![UZI-inlogmiddelen flows-combination-flow-diagram](https://user-images.githubusercontent.com/12181969/229889972-aba96faf-34ba-4283-8c20-e9fcf558032f.png)

## Contribution

This project is a Proof of Concept and is not intended to be a fully-fledged production application.

For that reason we will only accept contributions that fit this goal. We do appreciate any effort from the
community, but because our time is limited it is possible that your PR or issue is closed without a full justification.

If you plan to make non-trivial changes, we recommend to open an issue beforehand where we can discuss your planned changes. This increases the chance that we might be able to use your contribution (or it avoids doing work if there are reasons why we wouldn't be able to use it).

Note that all commits should be signed using a gpg key.
