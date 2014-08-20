<?php

namespace Fox\ElasticsearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FoxElasticsearchExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $params = [];
        foreach ($config['clients'] as $host) {
            $params['hosts'][] = $host['host'] . ":" . $host['port'];
        }

        if (!empty($params)) {
            $container->get('fox.elasticsearch_service.factory')->addParams($params);
        }

        $this->loadElasticsearchServices($config, $container);
    }

    /**
     * Loads elasticsearch services
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    protected function loadElasticsearchServices($config, ContainerBuilder $container)
    {
        foreach ($config['indexes'] as $name => $setting) {
            $id = $this->getServiceId($name, $setting['default']);
            unset($setting['default']);

            $service = new Definition(
                'Fox\ElasticsearchBundle\Service\ElasticsearchService',
                [
                    ['index' => $name, 'body' => $setting]
                ]
            );
            $service->setFactoryService('fox.elasticsearch_service.factory');
            $service->setFactoryMethod('get');

            $container->setDefinition($id, $service);
        }
    }

    /**
     * Returns elasticsearch index service id
     *
     * @param string $index
     * @param bool $default
     *
     * @return string
     */
    protected function getServiceId($index, $default = false)
    {
        if ($default) {
            return 'fox.elasticsearch';
        }

        return sprintf('fox.elasticsearch.%s', Container::camelize($index));
    }
}
