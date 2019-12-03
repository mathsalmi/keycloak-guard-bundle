<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Service;

use ACSystems\KeycloakGuardBundle\Entity\KeycloakUser;

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
     * @return KeycloakUser
     */
    public function createFromToken(array $token): KeycloakUser
    {
        $clientId = $this->clientId === '@azp'
            ? $token['azp']
            : $this->clientId;

        $roles = $this->getRoles($token['resource_access'], $clientId);

        return new KeycloakUser(
            $token['sub'],
            $token['preferred_username'],
            $roles,
            $token
        );
    }

    /**
     * @param array $resourceAccess
     * @param string $clientId
     * @return string[]
     */
    private function getRoles(array $resourceAccess, string $clientId): array
    {
        if (empty($resourceAccess[$clientId])) {
            return ['ROLE_USER'];
        }

        $kcRoles = $resourceAccess[$clientId]['roles'];
        $roles = array_map(static function (string $role): string {
            return strpos($role, 'ROLE_') === false ? 'ROLE_' . $role : $role;
        }, $kcRoles);
        // Required by Symfony
        $roles[] = 'ROLE_USER';

        return $roles;
    }
}