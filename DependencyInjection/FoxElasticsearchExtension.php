<?php

namespace Fox\ElasticsearchBundle\DependencyInjection;

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
        foreach ($config['connections'] as $name => $setting) {
            $params = [];
            foreach ($setting['hosts'] as $host) {
                $params['hosts'][] = $host['host'] . ':' . $host['port'];
            }

            if (!empty($params)) {
                $container->get('es.connection_factory')->addParams($params);
            }

            $service = new Definition(
                'Fox\ElasticsearchBundle\Service\Connection',
                [
                    [
                        'index' => $setting['index_name'],
                        'body' => ['settings' => $setting['settings']]
                    ]
                ]
            );
            $service->setFactoryService('es.connection_factory');
            $service->setFactoryMethod('get');

            $container->setDefinition(
                $this->getServiceId($name),
                $service
            );
        }
    }

    /**
     * Returns elasticsearch connection service id
     *
     * @param string $name
     *
     * @return string
     */
    protected function getServiceId($name)
    {
        return $name == 'default' ? 'es.connection' : sprintf('es.connection.%s', strtolower($name));
    }
}
