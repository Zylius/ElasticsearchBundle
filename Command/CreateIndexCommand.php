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

namespace Fox\ElasticsearchBundle\Command;

use Fox\ElasticsearchBundle\Service\ElasticsearchService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for creating elasticsrach index
 *
 * @package Fox\ElasticsearchBundle\Command
 */
class CreateIndexCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('es:index:create')
            ->setDescription('Creates elasticsearch index.')
            ->addOption(
                'index',
                null,
                InputOption::VALUE_REQUIRED,
                'Index name to create'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('index')) {
            $id = sprintf('fox.elasticsearch.%s', $input->getOption('index'));
        } else {
            $id = 'fox.elasticsearch';
        }

        /** @var ElasticsearchService $service */
        $service = $this->getContainer()->get($id);
        $service->createIndex();

        $output->writeln(sprintf('<info>Index %s created.</info>', $service->getIndexName()));
    }
}
