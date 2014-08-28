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
    /**
     * @return array
     */
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

    /**
     * Tests updateMapping with real data
     */
    public function testUpdateMapping()
    {
        $connection = $this->getConnection('bar');

        //using phpunit setExpectedException does not continue after exception is thrown.
        $thrown = false;
        try {
            $connection->updateMapping();
        } catch (\LogicException $e) {
            $thrown = true;
            //continue
        }
        $this->assertTrue($thrown, '\LogicException should be thrown');

        $connection = $this->getConnection(
            'barAcme',
            false,
            $connection->getIndexName(),
            $this->getAcmeMapping()
        );

        $status = $connection->updateMapping();
        $this->assertTrue($status, 'Mapping should be updated');
    }
}
