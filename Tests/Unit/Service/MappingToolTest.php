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

use ElasticsearchBundle\Service\MappingTool;

/**
 * Test for comparing arrays/mappings
 */
class MappingToolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testSymDiff
     *
     * @return array
     */
    public function getTestSymDiffData()
    {
        return [
            //case #0: no difference
            [
                ['a' => 4, 'b' => 6],
                ['a' => 4, 'b' => 6],
                []
            ],
            //case #1: basic difference
            [
                ['a' => 4, 'b' => 6, 'c' => 10],
                ['a' => 4, 'b' => 6],
                ['c' => 10]
            ],
            //case #2: second array is diffrent
            [
                ['a' => 4, 'b' => 6],
                ['a' => 4, 'b' => 6, 'c' => 10],
                ['c' => 10]
            ],
            //case #3: both arrays are missing data
            [
                ['a' => 4, 'b' => 6],
                ['a' => 4, 'c' => 10],
                ['b' => 6, 'c' => 10]
            ],
            //case #3: multi level difference
            [
                [
                    'a' => 4,
                    'b' => 6,
                    'd' => [
                        'f' => 9,
                        'g' => 18,
                        'h' => ['p' => 78, 'foo']
                    ]
                ],
                [
                    'a' => 4,
                    'd' => [
                        'f' => 9,
                        'g' => 19,
                        'r' => 7,
                        'h' => ['y' => 78]
                    ]
                ],
                [
                    'b' => 6,
                    'd' => [
                        'g' => 19,
                        'r' => 7,
                        'h' => ['p' => 78, 'y' => 78, 'foo']
                    ]
                ]
            ]
        ];
    }

    /**
     * Tests if arrays are compared as expected
     *
     * @param array $array1 array compare to
     * @param array $array2 array against
     * @param array $expected
     *
     * @dataProvider getTestSymDiffData
     */
    public function testSymDiff($array1, $array2, $expected)
    {
        $tool = new MappingTool($array1);
        $diff = $tool->symDifference($array2);

        $this->assertEquals($expected, $diff);
    }
}
