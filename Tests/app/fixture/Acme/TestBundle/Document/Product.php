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

namespace ElasticsearchBundle\Tests\app\fixture\Acme\TestBundle\Document;

use ElasticsearchBundle\Annotation as ES;

/**
 * @ES\Document
 */
final class Product
{
    /**
     * @var string
     *
     * @ES\Property(type="string", name="id", index="not_analyzed")
     */
    public $id;

    /**
     * @var string
     *
     * @ES\Property(type="string", name="title")
     */
    public $title;
}
