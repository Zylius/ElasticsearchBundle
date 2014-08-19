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

class ElasticsearchService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Holds index information. Similar structure to elasticsearch docs.
     *
     * array(
     *      'name' => 'index name'
     *      'body' => [
     *          'settings' => ['settings array']
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
    public function __construct(Client $client, $indexName)
    {
        $this->client = $client;
        $this->indexName = $indexName;
    }

    /**
     * Creates elasticsearch index
     */
    public function createIndex()
    {
        $this->client->create($this->index);
    }

    /**
     * Drops elasticsearch index
     */
    public function dropIndex()
    {
        $this->client->delete(['name' => $this->index['name']]);
    }

    /**
     * Returns index name this service is attached to
     *
     * @return string
     */
    public function getIndexName()
    {
        return $index['name'];
    }
}
