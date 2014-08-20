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
use Fox\ElasticsearchBundle\Service\ElasticsearchService;

/**
 * Factory for elasticsearch service
 *
 * @package Fox\ElasticsearchBundle\Factory
 */
class ElasticsearchServiceFactory
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
        return new ElasticsearchService(
            new Client($this->params),
            $index
        );
    }

    /**
     * Sets client parameters
     *
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Adds client parameters
     *
     * @param array $params
     */
    public function addParams($params)
    {
        $this->params = array_replace($this->params, $params);
    }
}
