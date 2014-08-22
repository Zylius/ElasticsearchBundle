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

namespace Fox\ElasticsearchBundle\Tests\Functional\DependencyInjection;

use Fox\ElasticsearchBundle\Service\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FoxElasticsearchExtensionTest extends WebTestCase
{
    /**
     * @return array
     */
    public function getTestContainerData()
    {
        return [
            ['es.connection_factory', 'Fox\ElasticsearchBundle\Factory\ConnectionFactory'],
            ['es.connection', 'Fox\ElasticsearchBundle\Service\Connection'],
            ['es.connection.bar', 'Fox\ElasticsearchBundle\Service\Connection'],
        ];
    }

    /**
     * Tests if container has all services
     *
     * @param $id string
     * @param $instance string
     *
     * @dataProvider getTestContainerData
     */
    public function testContainer($id, $instance)
    {
        $container = static::createClient()->getContainer();

        $this->assertTrue($container->has($id), 'Container should have setted id.');
        $this->assertInstanceOf($instance, $container->get($id), 'Container has wrong instance set to id.');
    }

    /**
     * Tests if mapping is gatherd correctly.
     * Mapping is loaded from fixture bundle in Tests/app/fixture
     */
    public function testMapping()
    {
        $container = static::createClient()->getContainer();

        $this->assertArrayHasKey(
            'AcmeTestBundle',
            $container->getParameter('kernel.bundles'),
            'Test bundle is not loaded.'
        );

        /** @var Connection $connection */
        $connection = $container->get('es.connection');
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
            $connection->getMapping('product'),
            'Incorrect mapping loaded'
        );
    }
}
