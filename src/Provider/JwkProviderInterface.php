<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Provider;

/**
 * Interface JwkProviderInterface
 * @package ACSystems\JWTAuthBundle\Provider
 */
interface JwkProviderInterface
{
    /**
     * @param bool $useCache
     * @return array
     */
    public function getJwks(bool $useCache = true): array;
}