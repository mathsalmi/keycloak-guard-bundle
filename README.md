# Keycloak Symfony Guard Bundle

The goal of this bundle is to provide a Keycloak token authenticator guard for Symfony.

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
      id: ACSystems\KeycloakGuardBundle\Security\Provider\KeycloakUserProvider
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

Add your keycloak base url and realm

`config/packages/keycloak_client.yaml:`
```yaml
acsystems_keycloak_guard:
  keycloak_guard:
    base_uri: 'https://example.com/'
    realm: 'example-realm'
```

#### Configurable parameters

| Name | Type | Usage |
| --- | --- | --- |
| base_uri | string | URL to your keycloak instance |
| realm | optional string | Realm name, will be derived if not present |
| client_id | optional string | Human readable client_id, will be derived if not present |

### Upgrading
For version migrations instructions see [upgrade instructions](./UPGRADE.md).

### Supported platforms

These are the platforms which are officially supported by this package.
Any other versions might work but is not guaranteed.

| Platform | Version |
| --- | ---: |
| PHP | 7.1 / 7.2 / 7.3 |

### Contributing

Please read our [contribution guidelines](./CONTRIBUTING.md) before contributing.
