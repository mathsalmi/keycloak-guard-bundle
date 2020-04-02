# Upgrades

## 0.1 to 1.0
`jwks_uri` param is no longer supported, instead use `base_uri` and optionally `realm`. Example:
```yaml
acsystems_keycloak_guard:
  keycloak_guard:
    -jwks_uri: 'https://example.com/auth/realms/example-realm/protocol/openid-connect/certs'
    +base_uri: 'https://example.com/'
    +realm: 'example-realm'
```
When realm is not present in the configuration, the realm will be automatically derived from the token's `iss` field.
It is recommended to add the realm field as an additional check. This change was made to allow a single application to access user pools from different realms.