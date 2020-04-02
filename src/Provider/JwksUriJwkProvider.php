<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Provider;

use Firebase\JWT\JWK;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use UnexpectedValueException;

/**
 * Class JsonJwkProvider
 * @package ACSystems\JWTAuthBundle\Provider
 */
class JwksUriJwkProvider implements JwkProviderInterface
{
    private const JWKS_CACHE = 'acsystems_keycloak_guard_jwk_json_';

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string | null
     */
    private $realm;

    /**
     * JsonJwkProvider constructor.
     * @param CacheInterface $cache
     * @param string|null $baseUrl
     * @param string|null $realm
     */
    public function __construct(CacheInterface $cache, ?string $baseUrl, ?string $realm)
    {
        $this->cache = $cache;
        $this->baseUrl = $baseUrl;
        $this->realm = $realm;
    }

    /**
     * @param string $token
     * @param bool $useCache
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getJwks(string $token, bool $useCache = true): array
    {
        $jwksPayload = $this->getJson($token, $useCache);
        try {
            return JWK::parseKeySet($jwksPayload);
        } catch (UnexpectedValueException $ex) {
            throw new \InvalidArgumentException('Invalid JWKS payload');
        }
    }

    /**
     * @param string $token
     * @param bool $useCache
     * @return string
     */
    private function getJson(string $token, bool $useCache): string
    {
        $realm = $this->getRealm($token);
        $url = "{$this->baseUrl}auth/realms/$realm/protocol/openid-connect/certs";

        if ($useCache === false) {
            return file_get_contents($url);
        }

        return $this->cache->get(self::JWKS_CACHE . $realm, static function (ItemInterface $item) use ($url): string {
            $item->expiresAfter(3600);

            return file_get_contents($url);
        });
    }

    /**
     * @param string $token
     * @return string
     */
    private function getRealm(string $token): string
    {
        if ($this->realm !== null) {
            return $this->realm;
        }
        $tokenParts = explode('.', $token);
        $tokenBody = json_decode(base64_decode($tokenParts[1]), true);
        $issuerParts = explode('/', $tokenBody['iss']);
        return end($issuerParts);
    }
}
