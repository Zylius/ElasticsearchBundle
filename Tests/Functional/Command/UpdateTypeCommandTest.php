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

use ElasticsearchBundle\Command\UpdateTypeCommand;
use ElasticsearchBundle\Test\BaseTest;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateTypeCommandTest extends BaseTest
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
            ['bar', true]
        ];
    }

    /**
     * Tests creating index. Configuration from tests yaml
     *
     * @param string $connection
     * @param bool $exceptionExpected
     *
     * @dataProvider getTestExecuteData
     */
    public function testExecute($connection, $exceptionExpected = false)
    {
        $this->getConnection($connection);
        $app = new Application();
        $app->add($this->getUpdateTypeCommand());

        //creates index
        $command = $app->find('es:type:update');
        $commandTester = new CommandTester($command);

        $exceptionExpected && $this->setExpectedException('\LogicException');

        $commandTester->execute([
            'command' => $command->getName(),
            '--connection' => $connection
        ]);

        !$exceptionExpected && $this->assertContains('type', strtolower($commandTester->getDisplay()));
    }

    /**
     * Returns create index command with setted container
     *
     * @return UpdateTypeCommand
     */
    protected function getUpdateTypeCommand()
    {
        $command = new UpdateTypeCommand();
        $command->setContainer($this->getContainer());

        return $command;
    }
}
