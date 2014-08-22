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

namespace Fox\ElasticsearchBundle\Annotation;

/**
 * Annotation that can be used to signal to the parser
 * to check mapping type during the parsing process.
 *
 * @Annotation
 */
final class Type
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $index;

    /**
     * @var string
     */
    public $analyzer;

    /**
     * @var string
     */
    public $index_analyzer;

    /**
     * @var string
     */
    public $search_analyzer;

    /**
     * @var float
     */
    public $boost;

    /**
     * @var bool
     */
    public $payloads;

    /**
     * Filters object null values and name
     *
     * @return array
     */
    public function filter()
    {
        return array_diff_key(
            array_filter(get_object_vars($this)),
            array_flip(['name'])
        );
    }
}
