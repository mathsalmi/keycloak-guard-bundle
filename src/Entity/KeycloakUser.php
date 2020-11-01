<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class KeycloakParsedToken
 * @package ACSystems\KeycloakGuardBundle\Entity
 */
class KeycloakUser implements ParsedTokenInterface, UserInterface
{
    /** @var string */
    private $id;

    /** @var string */
    private $username;

    /** @var array */
    private $roles;

    /** @var array */
    private $parsedToken;

    /** @var string */
    private $token;

    /**
     * KeycloakUser constructor.
     * @param string $id
     * @param string $username
     * @param array $roles
     * @param array $parsedToken
     * @param string $token
     */
    public function __construct(
        string $id,
        string $username,
        array $roles,
        array $parsedToken,
        string $token
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->roles = $roles;
        $this->parsedToken = $parsedToken;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Can be used to extract attributes from the token. Ex.: 'name'
     *
     * @param string $attribute
     * @return mixed|null
     */
    public function getAttribute(string $attribute)
    {
        return $this->parsedToken[$attribute] ?? null;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string|null The encoded password if any
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
