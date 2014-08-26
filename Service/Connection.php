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

namespace ElasticsearchBundle\Service;

use Elasticsearch\Client;

/**
 * This class interacts with elasticsearch using injected client
 */
class Connection
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Holds index information. Similar structure to elasticsearch docs.
     *
     * array(
     *      'index' => 'index name'
     *      'body' => [
     *          'settings' => [...],
     *          'mappings' => [...]
     *      ]
     * )
     *
     * @var array
     */
    protected $index;

    /**
     * @param Client $client
     * @param array $index index settings
     */
    public function __construct(Client $client, $index)
    {
        $this->client = $client;
        $this->index = $index;
    }

    /**
     * Creates fresh connection index
     */
    public function createIndex()
    {
        $this->client->indices()->create($this->index);
    }

    /**
     * Drops connection index
     */
    public function dropIndex()
    {
        $this->client->indices()->delete(['index' => $this->getIndexName()]);
    }

    /**
     * Tries to drop and create fresh connection index
     */
    public function dropAndCreateIndex()
    {
        try {
            $this->dropIndex();
        } catch (\Exception $e) {
            //Do nothing because I'm only trying
        }

        $this->createIndex();
    }

    /**
     * Checks if connection index is already created
     *
     * @return bool
     */
    public function indexExists()
    {
        return $this->client->indices()->exists(['index' => $this->getIndexName()]);
    }

    /**
     * Returns index name this connection is attached to
     *
     * @return string
     */
    public function getIndexName()
    {
        return $this->index['index'];
    }

    /**
     * Returns mapping by type
     *
     * @param string $type
     *
     * @return array|null
     */
    public function getMapping($type)
    {
        if (array_key_exists($type, $this->index['body']['mappings'])) {
            return $this->index['body']['mappings'][$type];
        }

        return null;
    }
}
