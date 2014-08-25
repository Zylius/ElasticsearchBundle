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

namespace ElasticsearchBundle\Tests\Unit\Service;


use Elasticsearch\Client;
use ElasticsearchBundle\Service\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if right values are being taken out
     */
    public function testGetters()
    {
        $config = [
            'index' => 'indexName',
            'body' => [
                'mappings' => [
                    'testMapping' => [
                        'properties' => []
                    ]
                ]
            ]
        ];

        $connection = new Connection(new Client(), $config);

        $this->assertEquals(
            'indexName',
            $connection->getIndexName(),
            'Recieved wrong index name'
        );
        $this->assertNull(
            $connection->getMapping('product'),
            'should not contain product mapping'
        );
        $this->assertArrayHasKey(
            'properties',
            $connection->getMapping('testMapping'),
            'should contain test mapping'
        );
    }
}
