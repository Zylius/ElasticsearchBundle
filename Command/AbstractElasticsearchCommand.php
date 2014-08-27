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

use ElasticsearchBundle\Service\ElasticsearchConnection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractElasticsearchCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addOption(
            'connection',
            null,
            InputOption::VALUE_REQUIRED,
            'Set index to work with.'
        );
    }

    /**
     * Returns elasticsearch connection by name
     *
     * @param string $name
     *
     * @return ElasticsearchConnection
     */
    protected function getConnection($name)
    {
        return $this
            ->getContainer()
            ->get($this->getConnectionId($name));
    }

    /**
     * Returns connection service id
     *
     * @param string $name
     *
     * @return string
     */
    private function getConnectionId($name)
    {
        if (!$name) {
            return 'es.connection';
        }
        return sprintf('es.connection.%s', strtolower($name));
    }
}
