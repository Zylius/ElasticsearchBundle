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

namespace Fox\ElasticsearchBundle\Factory;

use Elasticsearch\Client;
use Fox\ElasticsearchBundle\Service\ElasticsearchConnection;
use Psr\Log\LoggerInterface;

/**
 * Factory for elasticsearch connection
 *
 * @package Fox\ElasticsearchBundle\Factory
 */
class ElasticsearchConnectionFactory
{
    /**
     * Default params for elasticsearch-php client
     *
     * @var array
     */
    protected $params = [
        'logging' => true
    ];

    /**
     * Returns Elasticsearch service instance
     *
     * @param array $index index information
     *
     * @return ElasticsearchService
     */
    public function get(array $index)
    {
        return new ElasticsearchConnection(
            new Client($this->params),
            $index
        );
    }

    /**
     * Sets parameters to client
     *
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Adds parameters to client
     *
     * @param array $params
     */
    public function addParams($params)
    {
        $this->params = array_replace($this->params, $params);
    }
}
