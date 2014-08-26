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
use ElasticsearchBundle\Test\Base;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DropIndexCommandTest extends Base
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
        $this->setUpConnection($connection, true);

        $app = new Application();
        $app->add($this->getDropCommand());

        //does not drop index
        $command = $app->find('es:index:drop');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--connection' => $connection,
        ]);
        $this->assertTrue($this->connection->indexExists(), 'Index should still exist.');

        //does drop index
        $commandTester->execute([
            'command' => $command->getName(),
            '--connection' => $connection,
            '--force' => true,
        ]);

        $this->assertFalse($this->connection->indexExists(), 'Index should be dropped.');
    }

    /**
     * {@inhertidoc}
     */
    protected function setUp()
    {
        //nothing to do
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        //nothing to do
    }

    /**
     * Sets up connection to work with from container
     *
     * @param string $connection
     * @param bool $createIndex
     */
    protected function setUpConnection($connection = '', $createIndex = false)
    {
        if (!$connection) {
            $this->connection = $this->getContainer()->get("es.connection");
        } else {
            $this->connection = $this->getContainer()->get("es.connection.{$connection}");
        }

        $createIndex && $this->connection->createIndex();
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
