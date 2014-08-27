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

namespace ElasticsearchBundle\Tests\Functional\Service;

use ElasticsearchBundle\Test\BaseTest;

/**
 * Functional tests for connection service
 */
class ConnectionTest extends BaseTest
{
    protected function getAcmeMapping()
    {
        return [
            'mappings' => [
                'product' => [
                    'properties' => [
                        'id' => [
                            'type' => 'string',
                            'index' => 'not_analyzed'
                        ],
                        'title' => [
                            'type' => 'string'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function testUpdateMapping()
    {
        $connection = $this->getConnection('bar');

        $status = $connection->updateMapping();
        $this->assertEquals(-1, $status, 'Mapping should not be found.');

        $connection = $this->getConnection(
            'barAcme',
            false,
            $connection->getIndexName(),
            $this->getAcmeMapping()
        );

        $status = $connection->updateMapping();
        $this->assertEquals(1, $status, 'Mapping should be updated');
    }
}
