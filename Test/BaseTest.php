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

use ElasticsearchBundle\Client\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Base test which creates unique connection to test with
 */
abstract class BaseTest extends WebTestCase
{
    /**
     * Holds used connection names
     *
     * @var Connection[]
     */
    private $connections = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->getContainer();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        foreach ($this->connections as $name) {
            try {
                $this
                    ->getContainer()
                    ->get($this->getConnectionId($name))
                    ->dropIndex();
            } catch (\Exception $e) {
                //do nothing
            }
        }
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        if ($this->container) {
            return $this->container;
        }

        return $this->container = self::createClient()->getContainer();
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
     * Returns connection instance if does not exist creates new one
     *
     * @param string $name
     * @param bool $createIndex
     * @param array $customName index name
     * @param array $customConfig index config
     *
     * @return Connection
     */
    protected function getConnection($name = 'default', $createIndex = true, $customName = '', $customConfig = [])
    {
        $name = empty($name) ? 'default' : $name;

        //looks for cached connection
        if (in_array($name, $this->connections)) {
            return $this->getContainer()->get($this->getConnectionId($name));
        }

        $id = $this->getConnectionId($name);

        //looks in container otherwise creates new one
        try {
            $connection = $this
                ->getContainer()
                ->get($id);
        } catch (ServiceNotFoundException $e) {
            $index = [
                'index' => empty($customName) ? uniqid('elasticsearch_') : $customName
            ];

            $config = empty($customConfig) ? $this->getIndexConfig() : $customConfig;
            !empty($config) && $index['body'] = $config;

            $connection = $this->getContainer()->get('es.connection_factory')->get($index);
            $this->getContainer()->set($id, $connection);
        }

        $createIndex && $connection->dropAndCreateIndex();
        $this->connections[] = $name;

        return $connection;
    }

    /**
     * Formats service id for connection
     *
     * @param string $name
     *
     * @return string
     */
    private function getConnectionId($name)
    {
        return $name == 'default' ? 'es.connection' : sprintf("es.connection.%s", $name);
    }
}
