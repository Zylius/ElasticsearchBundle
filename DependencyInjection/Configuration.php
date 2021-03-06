<?php

namespace ElasticsearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elasticsearch');

        $rootNode
            ->children()
                ->arrayNode('connections')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->info('Defines connections to indexes and its settings.')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('hosts')
                                ->info('Defines hosts to connect to.')
                                ->defaultValue([])
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('host')
                                            ->info('Sets host for connection.')
                                            ->defaultValue('127.0.0.1')
                                        ->end()
                                        ->integerNode('port')
                                            ->info('Sets port for connection.')
                                            ->defaultValue(9200)
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('index_name')
                                ->isRequired()
                                ->info('Sets index name for connection.')
                            ->end()
                            ->arrayNode('settings')
                                ->defaultValue([])
                                ->info('Sets index settings for connection.')
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('document_managers')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->info('Mapps managers to connections and bundles')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('connection')
                                ->isRequired()
                                ->info('Sets connection for manager.')
                            ->end()
                            ->arrayNode('mappings')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->info('Mapps manager to bundles. f.e. AcmeDemoBundle')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
