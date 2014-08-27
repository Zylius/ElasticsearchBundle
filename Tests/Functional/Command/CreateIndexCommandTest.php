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
use ElasticsearchBundle\Test\BaseTest;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateIndexCommandTest extends BaseTest
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
     * Tests creating index. Configuration from tests yaml
     *
     * @param string $connection
     *
     * @dataProvider getTestExecuteData
     */
    public function testExecute($connection)
    {
        $this->getConnection($connection, false);

        $app = new Application();
        $app->add($this->getCreateCommand());

        //creates index
        $command = $app->find('es:index:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--connection' => $connection
        ]);

        $this->assertTrue($this->getConnection()->indexExists(), 'Index should exist.');
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
}
