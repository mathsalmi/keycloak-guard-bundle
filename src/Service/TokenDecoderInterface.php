<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Service;

/**
 * Interface TokenDecoder
 * @package ACSystems\KeycloakGuardBundle\Service
 */
interface TokenDecoderInterface
{
    /**
     * @param string $token
     * @return array
     */
    public function decodeToken(string $token): array;
}
