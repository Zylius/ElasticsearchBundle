<?php

/*
 *************************************************************************
 * NFQ eXtremes CONFIDENTIAL
 * [2013] - [2014] NFQ eXtremes UAB
 * All Rights Reserved.
 *************************************************************************
 * NOTICE: 
 * All information contained herein is, and remains the property of NFQ eXtremes UAB.
 * Dissemination of this information or reproduction of this material is strictly forbidden
 * unless prior written permission is obtained from NFQ eXtremes UAB.
 *************************************************************************
 */

namespace Fox\ElasticsearchBundle\Tests\Unit\DependencyInjection;


use Fox\ElasticsearchBundle\Factory\ConnectionFactory;
use Fox\ElasticsearchBundle\DependencyInjection\FoxElasticsearchExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FoxElasticsearchExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if bundle not found exception is thrown
     *
     * @expectedException \LogicException
     */
    public function testMappingException()
    {
        $config = $this->getDefaultConfig();
        $container = new ContainerBuilder();

        $container->set('es.connection_factory', new ConnectionFactory());
        $container->setParameter('kernel.bundles', []);

        $extension = new FoxElasticsearchExtension();
        $extension->load($config, $container);
    }

    /**
     * Tests if mapping is loaded correctly
     */
    public function testMapping()
    {
        $config = array_replace_recursive(
            $this->getDefaultConfig(),
            [
                'fox_elasticsearch' => [
                    'document_managers' => [
                        'default' => [
                            'mappings' => [
                                'AcmeTestBundle'
                            ]
                        ],
                        'custom' => [
                            'connection' => 'default',
                            'mappings' => [
                                'AcmeTestBundle',
                                'FoxElasticsearchBundle'
                            ]
                        ]
                    ]
                ]
            ]
        );
        $container = new ContainerBuilder();

        $container->set('es.connection_factory', new ConnectionFactory());
        $container->setParameter(
            'kernel.bundles',
            [
                'AcmeTestBundle' => 'Fox\ElasticsearchBundle\Tests\app\fixture\Acme\TestBundle\AcmeTestBundle',
                'FoxElasticsearchBundle' => 'Fox\ElasticsearchBundle\FoxElasticsearchBundle'
            ]
        );

        $extension = new FoxElasticsearchExtension();
        $extension->load($config, $container);

        $this->assertEquals(
            [
                'properties' => [
                    'id' => [
                        'type' => 'string',
                        'index' => 'not_analyzed'
                    ],
                    'title' => [
                        'type' => 'string'
                    ]
                ]
            ],
            $container->get('es.connection')->getMapping('product')
        );
    }

    /**
     * Elasticsearch sample config tree
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return [
            'fox_elasticsearch' => [
                'connections' => [
                    'default' => [
                        'hosts' => [
                            ['host' => 'localhost', 'port' => 9200]
                        ],
                        'index_name' => 'testIndex'
                    ]
                ],
                'document_managers' => [
                    'default' => [
                        'connection' => 'default',
                        'mappings' => [
                            'DoesNotExistBundle'
                        ]
                    ]
                ]
            ]
        ];
    }
}
