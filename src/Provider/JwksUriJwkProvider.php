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
use Symfony\Component\Cache\Adapter\AdapterInterface;
use UnexpectedValueException;

/**
 * Class JsonJwkProvider
 * @package ACSystems\JWTAuthBundle\Provider
 */
class JwksUriJwkProvider implements JwkProviderInterface
{
    private const JWKS_CACHE = 'acsystems_keycloak_guard_jwk_json_';

    /**
     * @var AdapterInterface
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
     * @param AdapterInterface $cache
     * @param string|null $baseUrl
     * @param string|null $realm
     */
    public function __construct(AdapterInterface $cache, ?string $baseUrl, ?string $realm)
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

        $cacheKey = self::JWKS_CACHE . $realm;
        $item = $this->cache->getItem($cacheKey);
        if(!$item->isHit()) {
            $item->expiresAfter(3600);
            $item->set(file_get_contents($url));
            $this->cache->save($item);
        }

        return $item->get();
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
