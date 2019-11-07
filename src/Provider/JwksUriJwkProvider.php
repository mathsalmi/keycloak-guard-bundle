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
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $jwksUri;

    /**
     * JsonJwkProvider constructor.
     * @param CacheInterface $cache
     * @param string $jwksUri
     */
    public function __construct(CacheInterface $cache, string $jwksUri = '')
    {
        $this->cache = $cache;
        $this->jwksUri = $jwksUri;
    }

    /**
     * @param bool $useCache
     * @return array
     * @throws InvalidArgumentException
     */
    public function getJwks(bool $useCache = true): array
    {
        $jwksPayload = $this->getJson($useCache);
        try {
            return JWK::parseKeySet($jwksPayload);
        } catch (UnexpectedValueException $ex) {
            throw new \InvalidArgumentException('Invalid JWKS payload');
        }
    }

    /**
     * @param bool $useCache
     * @return string
     * @throws InvalidArgumentException
     */
    private function getJson(bool $useCache): string
    {
        if ($useCache === false) {
            return file_get_contents($this->jwksUri);
        }

        return $this->cache->get('acsystems_keycloak_guard_jwk_json', function (ItemInterface $item): string {
            $item->expiresAfter(3600);

            return file_get_contents($this->jwksUri);
        });
    }
}