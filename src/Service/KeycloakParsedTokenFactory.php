<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Service;

use ACSystems\KeycloakGuardBundle\Entity\KeycloakParsedToken;

/**
 * Class KeycloakParsedTokenFactory
 * @package ACSystems\KeycloakGuardBundle\Service
 */
class KeycloakParsedTokenFactory
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * KeycloakParsedTokenFactory constructor.
     * @param string $clientId
     */
    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @param array $token
     * @return KeycloakParsedToken
     */
    public function createFromToken(array $token): KeycloakParsedToken
    {
        $clientId = $this->clientId === '@azp'
            ? $token['azp']
            : $token[$this->clientId];
        $kcRoles = $token['resource_access'][$clientId]['roles'];
        $roles = array_map(static function (string $role): string {
            return strpos($role, 'ROLE_') === false ? 'ROLE_' . $role : $role;
        }, $kcRoles);
        // Required by Symfony
        $roles[] = 'ROLE_USER';

        return new KeycloakParsedToken(
            $token['sub'],
            $token['preferred_username'],
            $roles,
            $token
        );
    }
}