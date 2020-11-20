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
     * @param array $parsedToken
     * @param string $token
     * @return KeycloakUser
     */
    public function createFromToken(array $parsedToken, string $token): KeycloakUser
    {
        $clientId = $this->clientId === '@azp'
            ? $parsedToken['azp']
            : $this->clientId;

        $roles = $this->getRoles($parsedToken, $clientId);

        return new KeycloakUser(
            $parsedToken['sub'],
            $parsedToken['preferred_username'],
            $roles,
            $parsedToken,
            $token
        );
    }

    /**
     * @param array $token
     * @param string $clientId
     * @return string[]
     */
    protected function getRoles(array $token, string $clientId): array
    {
        if (empty($token['resource_access'][$clientId])) {
            return ['ROLE_USER'];
        }

        $kcRoles = $token['resource_access'][$clientId]['roles'] ?? [];
        $roles = array_map(static function (string $role): string {
            return strpos($role, 'ROLE_') === false ? 'ROLE_' . $role : $role;
        }, $kcRoles);
        // Required by Symfony
        $roles[] = 'ROLE_USER';

        return $roles;
    }
}
