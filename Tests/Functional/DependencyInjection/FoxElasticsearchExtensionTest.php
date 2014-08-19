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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FoxElasticsearchExtensionTest extends WebTestCase
{
    /**
     * @return array
     */
    public function getTestContainerData()
    {
        return [
            ['fox.elasticsearch_service.factory', 'Fox\ElasticsearchBundle\Factory\ElasticsearchServiceFactory'],
            ['fox.elasticsearch', 'Fox\ElasticsearchBundle\Service\ElasticsearchService'],
            ['fox.elasticsearch.fooIndex', 'Fox\ElasticsearchBundle\Service\ElasticsearchService'],
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
        $container = self::createClient()->getContainer();

        $this->assertTrue($container->has($id), 'Container should have setted id.');
        $this->assertInstanceOf($instance, $container->get($id), 'Container has wrong instance set to id.');
    }
}
