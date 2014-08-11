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

use Fox\ElasticsearchBundle\DependencyInjection\FoxElasticsearchExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FoxElasticsearchExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test extension loader
     */
    public function testLoad()
    {
        $extension = new FoxElasticsearchExtension();
        $container = new ContainerBuilder();

        $container->setParameter('elasticsearch.host', '127.0.0.1');

        $extension->load(array(), $container);

        $this->assertTrue($container->hasParameter('elasticsearch.host'));
        $this->assertEquals('127.0.0.1', $container->getParameter('elasticsearch.host'));
    }
}
