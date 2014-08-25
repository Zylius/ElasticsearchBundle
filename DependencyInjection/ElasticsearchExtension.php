<?php

namespace ElasticsearchBundle\DependencyInjection;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
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
class ElasticsearchExtension extends Extension
{
    /**
     * Annotations to load
     *
     * @var array
     */
    protected $annotations = ['Document', 'Property'];

    /**
     * Contains mappings gatherd from bundle documents
     *
     * @var array
     */
    protected $mappings = [];

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
            $index = [
                'index' => $setting['index_name'],
            ];

            !empty($setting['settings']) && $index['body']['settings'] = $setting['settings'];

            foreach ($setting['hosts'] as $host) {
                $params['hosts'][] = $host['host'] . ':' . $host['port'];
            }

            !empty($params) && $container->get('es.connection_factory')->addParams($params);

            $mappings = [];
            foreach ($config['document_managers'] as $managerSetting) {
                if ($managerSetting['connection'] == $name) {
                    foreach ($managerSetting['mappings'] as $bundle) {
                        $mappings = array_replace_recursive(
                            $mappings,
                            $this->getMapping($bundle, $container)
                        );
                    }
                }
            }

            !empty($mappings) && $index['body']['mappings'] = $mappings;

            $service = new Definition(
                'ElasticsearchBundle\Service\Connection',
                [$index]
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

    /**
     * Returns directory name in which all documents must be stored in bundles
     *
     * @return string
     */
    protected function getDocumentsDirectoryName()
    {
        return 'Document';
    }

    /**
     * Registers annotations to registry so that it could be used by reader
     */
    protected function registerAnnotations()
    {
        foreach ($this->annotations as $annotation) {
            AnnotationRegistry::registerFile(__DIR__."/../Annotation/{$annotation}.php");
        }
    }

    /**
     * Retrieves mapping from local cache otherwise runs through bundle files
     *
     * @param string $bundle
     * @param ContainerBuilder $container
     *
     * @return array
     */
    protected function getMapping($bundle, $container)
    {
        if (array_key_exists($bundle, $this->mappings)) {
            return $this->mappings[$bundle];
        }

        return $this->mappings[$bundle] = $this->getBundleMapping($bundle, $container);
    }

    /**
     * Searches for documents in bundle and tries to read them.
     * Returns empty array on containing zero documents
     *
     * @param string $bundle
     * @param ContainerBuilder $container
     *
     * @return array
     * @throws \LogicException
     */
    private function getBundleMapping($bundle, ContainerBuilder $container)
    {
        $kernelBundles = $container->getParameter('kernel.bundles');
        $this->registerAnnotations();
        $mappings = [];

        //Checks if bundle is register in kernel
        if (array_key_exists($bundle, $kernelBundles)) {
            $bundleReflection = new \ReflectionClass($kernelBundles[$bundle]);
            $documents = glob(
                dirname($bundleReflection->getFileName()) .
                DIRECTORY_SEPARATOR .
                $this->getDocumentsDirectoryName() .
                DIRECTORY_SEPARATOR .
                '*.php'
            );

            //Loop through documents found in bundle
            foreach ($documents as $document) {
                $filename = pathinfo($document, PATHINFO_FILENAME);
                $documentReflection = new \ReflectionClass(
                    $bundleReflection->getNamespaceName() . '\\' .
                    $this->getDocumentsDirectoryName() . '\\' .
                    $filename
                );

                $documentMapping = $this->getDocumentMapping($documentReflection);
                if (empty($documentMapping)) {
                    continue;
                }
                $mappings[strtolower($filename)]['properties'] = $documentMapping;
            }
        } else {
            throw new \LogicException("{$bundle} not found.");
        }

        return $mappings;
    }

    /**
     * Gethers annotation data from class
     *
     * @param \ReflectionClass $reflectionClass
     *
     * @return array|null
     */
    private function getDocumentMapping(\ReflectionClass $reflectionClass)
    {
        $mapping = [];
        $reader = new AnnotationReader();

        if ($reader->getClassAnnotation($reflectionClass, 'ElasticsearchBundle\Annotation\Document')) {
            /** @var \ReflectionProperty $property */
            foreach ($reflectionClass->getProperties() as $property) {
                $type = $reader->getPropertyAnnotation($property, 'ElasticsearchBundle\Annotation\Property');
                !empty($type) && $mapping[$type->name] = $type->filter();
            }

            return $mapping;
        }

        return null;
    }
}
