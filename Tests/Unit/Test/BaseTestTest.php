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
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Tests BaseTest
 */
class BaseTestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests getting not existing connection
     */
    public function testGettingNotExistingConnection()
    {
        $connectionMock = $this
            ->getMockBuilder('ElasticsearchBundle\Service\Connection')
            ->disableOriginalConstructor()
            ->getMock();
        $connectionMock
            ->expects($this->never())
            ->method('dropAndCreateIndex');

        $factory = $this->getMock('ElasticsearchBundle\Factory\ConnectionFactory', ['get']);
        $factory
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($connectionMock));

        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $containerMock
            ->expects($this->at(0))
            ->method('get')
            ->with('es.connection.random')
            ->will($this->throwException(new ServiceNotFoundException('es.connection.random')));
        $containerMock
            ->expects($this->at(1))
            ->method('get')
            ->with('es.connection_factory')
            ->will($this->returnValue($factory));
        $containerMock
            ->expects($this->once())
            ->method('set');

        $mock = $this
            ->getMockBuilder('ElasticsearchBundle\Tests\Unit\Test\BaseTestDummy')
            ->disableOriginalConstructor()
            ->getMock();
        $mock
            ->expects($this->any())
            ->method('getContainer')
            ->will($this->returnValue($containerMock));

        $reflection = new \ReflectionMethod($mock, 'getConnection');
        $reflection->setAccessible(true);
        $reflection->invokeArgs($mock, ['random', false]);
    }
}
