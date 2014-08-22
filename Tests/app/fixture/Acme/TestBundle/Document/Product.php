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

namespace Fox\ElasticsearchBundle\Tests\app\fixture\Acme\TestBundle\Document;

use Fox\ElasticsearchBundle\Annotation as ES;

/**
 * @ES\Document
 */
class Product
{
    /**
     * @var string
     *
     * @ES\Type(type="string", name="id", index="not_analyzed")
     */
    public $id;

    /**
     * @var string
     *
     * @ES\Type(type="string", name="title")
     */
    public $title;
}
