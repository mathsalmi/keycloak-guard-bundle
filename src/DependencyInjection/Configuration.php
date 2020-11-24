<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

// @formatter:off
/**
 * Class Configuration
 * @package ACSystems\KeycloakGuardBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder;
        /** @var ArrayNodeDefinition */
        $rootNode = $treeBuilder->root(ACSystemsKeycloakGuardExtension::EXTENSION_ALIAS);
        $rootNode
            ->children()
                ->arrayNode('keycloak_guard')
                    ->append($this->getClient())
                    ->append($this->getKeycloakUri())
                    ->append($this->getKeycloakRealm())
                ->end();

        return $treeBuilder;
    }

    /**
     * @return NodeDefinition
     */
    public function getClient(): NodeDefinition
    {
        $node = new ScalarNodeDefinition('client_id');
        $node
            ->defaultValue('@azp')
            ->validate()
                ->ifTrue(static function ($v) {
                    return !is_string($v);
                })
                ->thenInvalid('client_id must be a string.')
            ->end();
        return $node;
    }

    /**
     * @return NodeDefinition
     */
    public function getKeycloakUri(): NodeDefinition
    {
        $node = new ScalarNodeDefinition('base_uri');
        $node
            ->validate()
                ->ifTrue(static function ($v) {
                    return !is_string($v);
                })
                ->thenInvalid('base_uri must be a string');

        return $node;
    }

    public function getKeycloakRealm(): NodeDefinition
    {
        $node = new ScalarNodeDefinition('realm');
        $node->defaultNull();

        return $node;
    }
}
// @formatter:on
