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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for creating elasticsearch index
 */
class CreateIndexCommand extends AbstractElasticsearchCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('es:index:create')
            ->setDescription('Creates elasticsearch index.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Connection $connection */
        $connection = $this->getConnection($input->getOption('connection'));
        $connection->createIndex();

        $output->writeln(sprintf('<info>Index %s created.</info>', $connection->getIndexName()));
    }
}
