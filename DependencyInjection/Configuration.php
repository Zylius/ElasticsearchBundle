<?php

namespace Fox\ElasticsearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fox_elasticsearch');

        $rootNode
            ->children()
                ->arrayNode('clients')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->info('Defines connections to clients')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')
                                ->isRequired()
                                ->info('Sets host for connection.')
                                ->defaultValue('127.0.0.1')
                            ->end()
                            ->integerNode('port')
                                ->isRequired()
                                ->info('Sets port for connection.')
                                ->defaultValue(9200)
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('indexes')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->info('Defines indexes and its settings.')
                    ->prototype('array')
                        ->children()
                            ->booleanNode('default')
                                ->defaultFalse()
                                ->info('If set true could be accessed through fox.elasticsearch')
                            ->end()
                            ->arrayNode('settings')
                                ->isRequired()
                                ->prototype('variable')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
