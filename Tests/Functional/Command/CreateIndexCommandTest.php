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

use ElasticsearchBundle\Command\CreateIndexCommand;
use ElasticsearchBundle\Test\Base;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateIndexCommandTest extends Base
{
    /**
     * Execution data provider
     *
     * @return array
     */
    public function getTestExecuteData()
    {
        return [
            [''],
            ['bar']
        ];
    }

    /**
     * {@inhertidoc}
     */
    protected function setUp()
    {
        //nothing to do
    }

    /**
     * Tests creating index. Configuration from tests yaml
     *
     * @param string $connection
     *
     * @dataProvider getTestExecuteData
     */
    public function testExecute($connection)
    {
        $this->setUpConnection($connection);
        $app = new Application();
        $app->add($this->getCreateCommand());

        //creates index
        $command = $app->find('es:index:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--connection' => $connection
        ]);

        $this->assertTrue($this->connection->indexExists(), 'Index should exist.');
    }

    /**
     * Returns create index command with setted container
     *
     * @return CreateIndexCommand
     */
    protected function getCreateCommand()
    {
        $command = new CreateIndexCommand();
        $command->setContainer($this->getContainer());

        return $command;
    }

    /**
     * Sets up connection to work with from container
     *
     * @param string $connection
     */
    protected function setUpConnection($connection = '')
    {
        if (!$connection) {
            $this->connection = $this->getContainer()->get("es.connection");
        } else {
            $this->connection = $this->getContainer()->get("es.connection.{$connection}");
        }
    }
}
