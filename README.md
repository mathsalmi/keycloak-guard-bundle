# Keycloak Symfony Guard Bundle

The goal of this bundle is to provide a Keycloak authenticator guard for Symfony.

![License](https://img.shields.io/badge/license-MIT-brightgreen)
[![PHP](https://img.shields.io/badge/%3C%2F%3E-PHP%207.1-blue)](https://www.php.net/) 
[![Code Style](https://img.shields.io/badge/code%20style-psr--2-darkgreen)](https://www.php-fig.org/psr/psr-2/)

## Documentation

### Quick start

#### Installation

Install the package from packagist using composer

```console
composer require acsystems/keycloak-guard-bundle
```

Add the bundle.

`config/bundles.php`
```php
return [
    ACSystems\KeycloakGuardBundle\ACSystemsKeycloakGuardBundle::class => ['all' => true]
];
```

Set up Symfony Security to use the custom authenticator.

`config/packages/security.yaml`
```yaml
security:
  providers:
    keycloak:
      id: ACSystems\KeycloakGuardBundle\Security\Provider\KeycloakParsedTokenProvider
  firewalls:
    dev:
      pattern: ^/(_(profiler|wdt)|css|images|js)/
      security: false
    main:
      provider: keycloak
      stateless: true
      anonymous: true
      logout: ~

      guard:
        authenticators:
          - ACSystems\KeycloakGuardBundle\Security\KeycloakTokenAuthenticator

  access_control:
    # ...
```

#### Configuration

By default the azp inside of the token is used as client id. This can be overwritten.

`config/packages/keycloak_client.yaml:`
```yaml
keycloak_client:
    client_id: 'my-client-id'
```

### Supported platforms

These are the platforms which are officially supported by this package.
Any other versions might work but is not guaranteed.

| Platform | Version |
| --- | ---: |
| PHP | 7.1 / 7.2 / 7.3 |

### Contributing

Please read our [contribution guidelines](./CONTRIBUTING.md) before contributing.
