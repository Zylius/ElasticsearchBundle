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
     * @param string $name
     *
     * @return string
     */
    protected function getServiceId($name)
    {
        if (!$name) {
            return 'es.connection';
        }
        return sprintf('es.connection.%s', strtolower($name));
    }
}
