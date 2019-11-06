<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle;

use ACSystems\KeycloakGuardBundle\DependencyInjection\ACSystemsKeycloakGuardExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ACSystemsKeycloakGuardBundle
 * @package ACSystems\KeycloakGuardBundle
 */
class ACSystemsKeycloakGuardBundle extends Bundle
{
    /**
     * @return ExtensionInterface|null
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if ($this->extension === null) {
            $this->extension = new ACSystemsKeycloakGuardExtension();
        }
        return $this->extension;
    }
}