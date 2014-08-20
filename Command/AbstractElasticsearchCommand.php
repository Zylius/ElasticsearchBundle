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

namespace Fox\ElasticsearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractElasticsearchCommand extends ContainerAwareCommand
{
    /**
     * Gets service id
     *
     * @param string $index
     *
     * @return string
     */
    protected function getServiceId($index)
    {
        if (!$index) {
            return 'fox.elasticsearch';
        }
        return sprintf('fox.elasticsearch.%s', $index);
    }
}
