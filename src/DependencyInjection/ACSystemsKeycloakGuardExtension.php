<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ACSystemsKeycloakGuardExtension
 * @package ACSystems\KeycloakGuardBundle\DependencyInjection
 */
class ACSystemsKeycloakGuardExtension extends Extension
{
    public const EXTENSION_ALIAS = 'acsystems_keycloak_guard';

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return self::EXTENSION_ALIAS;
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $config = array_merge(['keycloak_client' => ['client_id' => '@azp']] , $config);

        $container->setParameter('keycloak_client.client_id', $config['keycloak_client']['client_id']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}