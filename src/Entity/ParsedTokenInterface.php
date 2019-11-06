<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Entity;

/**
 * Interface ParsedTokenInterface
 * @package ACSystems\KeycloakGuardBundle\Entity
 */
interface ParsedTokenInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string[]
     */
    public function getRoles(): array;

    /**
     * @param string $attribute
     * @return mixed|null
     */
    public function getAttribute(string $attribute);
}