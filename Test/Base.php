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

namespace ElasticsearchBundle\Test;

use ElasticsearchBundle\Service\Connection;
use ElasticsearchBundle\Factory\ConnectionFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base test which creates unique connection to test with
 */
abstract class Base extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $container = $this->getContainer();
        /** @var ConnectionFactory $factory */
        $index = [];
        $index['index'] = uniqid($this->getIndexNamePrefix() . '_');

        $config = $this->getIndexConfig();
        !empty($config) && $index['body'] = $config;

        $this->connection = $container->get('es.connection_factory')->get($index);
        $this->connection->dropAndCreateIndex();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->connection->dropIndex();
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        if ($this->container) {
            return $this->container;
        }

        try {
            return $this->container = self::createClient()->getContainer();
        } catch (\Exception $e) {
            print $e->getMessage();
        }
    }

    /**
     * Returns index body configuration
     *
     * @return array
     */
    protected function getIndexConfig()
    {
        return [];
    }

    /**
     * Returns prefix for test index names
     *
     * @return string
     */
    protected function getIndexNamePrefix()
    {
        return 'elasticsearch';
    }
}
