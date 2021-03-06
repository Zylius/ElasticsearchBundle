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

namespace ElasticsearchBundle\Command;

use ElasticsearchBundle\Client\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for dropping elasticsearch index
 */
class DropIndexCommand extends AbstractElasticsearchCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('es:index:drop')
            ->setDescription('Drops elasticsearch index')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Set this parameter to execute this command.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('force')) {
            /** @var Connection $connection */
            $connection = $this->getConnection($input->getOption('connection'));
            $connection->dropIndex();

            $output->writeln(sprintf('<info>Index %s has been dropped.</info>', $connection->getIndexName()));
        } else {
            $output->writeln('Parameter --force has to be used to drop the index.');
        }
    }
}
