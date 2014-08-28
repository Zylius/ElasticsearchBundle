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

namespace ElasticsearchBundle\Tests\Unit\Command;

use ElasticsearchBundle\Command\UpdateTypeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Unit tests for update type command
 */
class UpdateTypeCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testing command output
     *
     * @return array
     */
    public function getTextExecuteData()
    {
        $connectionMock = $this
            ->getMockBuilder('ElasticsearchBundle\Service\Connection')
            ->disableOriginalConstructor()
            ->getMock();

        $out = [];

        //case #0: status 1
        $container = new ContainerBuilder();
        $connMock1 = clone $connectionMock;
        $connMock1
            ->expects($this->once())
            ->method('updateMapping')
            ->will($this->returnValue(1));
        $container->set('es.connection', $connMock1);
        $expected = 'types updated';

        //case #1: status -1
        $container = new ContainerBuilder();
        $connMock2 = clone $connectionMock;
        $connMock2
            ->expects($this->once())
            ->method('updateMapping')
            ->will($this->returnValue(-1));
        $container->set('es.connection', $connMock2);
        $expected = 'no information found about types.';

        //case #1: status 0
        $container = new ContainerBuilder();
        $connMock3 = clone $connectionMock;
        $connMock3
            ->expects($this->once())
            ->method('updateMapping')
            ->will($this->returnValue(0));
        $container->set('es.connection', $connMock3);
        $expected = 'types are already up to date';

        $out[] = [$container, $expected];

        return $out;
    }

    /**
     * Tests command output
     *
     * @param ContainerBuilder $container
     * @param string $expected
     *
     * @dataProvider getTextExecuteData
     */
    public function testExecute($container, $expected)
    {
        $command = new UpdateTypeCommand();
        $command->setContainer($container);

        $app = new Application();
        $app->add($command);

        $command = $app->find('es:type:update');
        $tester = new CommandTester($command);
        $tester->execute([
            'command' => $command->getName()
        ]);

        $this->assertContains($expected, strtolower($tester->getDisplay()));
    }
}
