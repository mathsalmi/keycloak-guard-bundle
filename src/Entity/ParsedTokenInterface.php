<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
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