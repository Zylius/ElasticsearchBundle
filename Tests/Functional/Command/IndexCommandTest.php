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
use ElasticsearchBundle\Command\DropIndexCommand;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Tests create/drop index commands
 *
 * @package Fox\ElasticsearchBundle\Tests\Functional\Command
 */
class IndexCommandTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->container = static::createClient()->getContainer();
    }

    /**
     * Returns create index command with setted container
     *
     * @return CreateIndexCommand
     */
    protected function getCreateCommand()
    {
        $command = new CreateIndexCommand();
        $command->setContainer($this->container);

        return $command;
    }

    /**
     * Returns drop index command with setted container
     *
     * @return DropIndexCommand
     */
    protected function getDropCommand()
    {
        $command = new DropIndexCommand();
        $command->setContainer($this->container);

        return $command;
    }

    /**
     * Execution data provider
     *
     * @return array
     */
    public function getTestExecuteData()
    {
        return [
            [
                []
            ],
            [
                [
                    '--connection' => 'bar'
                ]
            ]
        ];
    }

    /**
     * Tests creating default index. If something goes wrong exceptions will be thrown
     *
     * @param array $input
     *
     * @dataProvider getTestExecuteData
     */
    public function testExecute($input)
    {
        $app = new Application();
        $app->add($this->getCreateCommand());
        $app->add($this->getDropCommand());

        //creates index
        $command = $app->find('es:index:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array_merge(
            $input,
            [
                'command' => $command->getName()
            ]
        ));

        $this->assertContains('created', $commandTester->getDisplay());

        //does not drop index
        $command = $app->find('es:index:drop');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array_merge(
            $input,
            [
                'command' => $command->getName()
            ]
        ));
        $this->assertNotFalse(strpos(
            $commandTester->getDisplay(),
            'Parameter --force has to be used to drop the index.'
        ));

        //drops index
        $commandTester->execute(array_merge(
            $input,
            [
                '--force' => true,
                'command' => $command->getName()
            ]
        ));

        $this->assertContains('dropped', $commandTester->getDisplay());
    }
}
