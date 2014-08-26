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

namespace ElasticsearchBundle\Tests\Unit\Test;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests base test
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests setup
     */
    public function testSetUp()
    {
        $connectionMock = $this
            ->getMockBuilder('ElasticsearchBundle\Service\Connection')
            ->disableOriginalConstructor()
            ->getMock();
        $connectionMock
            ->expects($this->once())
            ->method('dropAndCreateIndex');

        $factory = $this->getMock('ElasticsearchBundle\Factory\ConnectionFactory', ['get']);
        $factory
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($connectionMock));

        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with('es.connection_factory')
            ->will($this->returnValue($factory));

        $mock = $this
            ->getMockBuilder('ElasticsearchBundle\Test\BaseTest')
            ->disableOriginalConstructor()
            ->getMock();
        $mock
            ->expects($this->once())
            ->method('getContainer')
            ->will($this->returnValue($containerMock));

        $reflection = new \ReflectionMethod($mock, 'setUp');
        $reflection->setAccessible(true);
        $reflection->invoke($mock);
    }
}
