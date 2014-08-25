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

namespace ElasticsearchBundle\Tests\Unit\Annotation;

use ElasticsearchBundle\Annotation\Property;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if values are filtered correctly
     */
    public function testFilter()
    {
        $type = new Property();

        $type->name = 'id';
        $type->index = 'no_index';
        $type->type = 'string';
        $type->analyzer = null;

        $this->assertEquals(
            [
                'index' => 'no_index',
                'type' => 'string'
            ],
            $type->filter()
        );
    }
}
