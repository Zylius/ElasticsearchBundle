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

namespace Fox\ElasticsearchBundle\Service;

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
     * Creates elasticsearch index
     */
    public function createIndex()
    {
        $this->client->indices()->create($this->index);
    }

    /**
     * Drops elasticsearch index
     */
    public function dropIndex()
    {
        $this->client->indices()->delete(['index' => $this->index['index']]);
    }

    /**
     * Returns index name this service is attached to
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
