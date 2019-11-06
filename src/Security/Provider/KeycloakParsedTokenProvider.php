<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Security\Provider;

use ACSystems\KeycloakGuardBundle\Entity\KeycloakParsedToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class KeycloakParsedTokenProvider
 * @package ACSystems\KeycloakGuardBundle\Security\Provider
 */
class KeycloakParsedTokenProvider implements UserProviderInterface
{
    /**
     * @param $username
     * @return void
     */
    public function loadUserByUsername($username): void
    {
        throw new UsernameNotFoundException('This provider does not support loading users');
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function refreshUser(UserInterface $user): void
    {
        throw new UsernameNotFoundException('This provider does not support refreshing users');
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return $class === KeycloakParsedToken::class;
    }
}