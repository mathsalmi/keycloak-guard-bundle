<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder(ACSystemsKeycloakGuardExtension::EXTENSION_ALIAS);
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('keycloak_client')
                    ->append($this->getClient())
                ->end();

        return $treeBuilder;
    }

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
}
// @formatter:on