<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
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