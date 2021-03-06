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

class UpdateTypeCommand extends AbstractElasticsearchCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('es:type:update')
            ->setDescription('Creates mapping for elasticsearch');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Connection $connection */
        $connection = $this->getConnection($input->getOption('connection'));

        $result = $connection->updateMapping();

        if ($result === true) {
            $output->writeln('<info>Types updated.</info>');
        } elseif ($result === false) {
            $output->writeln('<info>Types are already up to date.</info>');
        } else {
            throw new \UnexpectedValueException('Expected boolean value from Connection::updateMapping()');
        }
    }
}
