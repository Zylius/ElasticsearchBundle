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

namespace ElasticsearchBundle\Tests\Functional\Command;

use ElasticsearchBundle\Command\DropIndexCommand;
use ElasticsearchBundle\Test\BaseTest;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DropIndexCommandTest extends BaseTest
{
    /**
     * Execution data provider
     *
     * @return array
     */
    public function getTestExecuteData()
    {
        return [
            [],
            ['bar']
        ];
    }

    /**
     * Tests dropping index. Configuration from tests yaml
     *
     * @param string $connection
     *
     * @dataProvider getTestExecuteData
     */
    public function testExecute($connection = '')
    {
        $this->getConnection($connection);

        $app = new Application();
        $app->add($this->getDropCommand());

        //does not drop index
        $command = $app->find('es:index:drop');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--connection' => $connection,
        ]);
        $this->assertTrue(
            $this
                ->getConnection($connection)
                ->indexExists(),
            'Index should still exist.'
        );

        //does drop index
        $commandTester->execute([
            'command' => $command->getName(),
            '--connection' => $connection,
            '--force' => true,
        ]);

        $this->assertFalse(
            $this
                ->getConnection($connection)
                ->indexExists(),
            'Index should be dropped.'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        //nothing to do
    }

    /**
     * Returns drop index command with setted container
     *
     * @return DropIndexCommand
     */
    protected function getDropCommand()
    {
        $command = new DropIndexCommand();
        $command->setContainer($this->getContainer());

        return $command;
    }
}
