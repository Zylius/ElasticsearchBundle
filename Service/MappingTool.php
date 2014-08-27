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

namespace ElasticsearchBundle\Service;

/**
 * Mapping tool for comparing
 */
class MappingTool
{
    /**
     * @var array
     */
    protected $mapping;

    /**
     * @param array $mapping
     */
    public function __construct($mapping = [])
    {
        $this->setMapping($mapping);
    }

    /**
     * Returns symmetric difference
     *
     * @param array $newMapping
     *
     * @return array
     */
    public function symDifference($newMapping)
    {
        return array_replace_recursive(
            $this->recursiveDiff($this->mapping, $newMapping),
            $this->recursiveDiff($newMapping, $this->mapping)
        );
    }

    /**
     * @param array $mapping
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * Recursively computes the difference of arrays
     *
     * @param array $compareFrom
     * @param array $compareAgainst
     *
     * @return array
     */
    protected function recursiveDiff($compareFrom, $compareAgainst)
    {
        $out = [];

        foreach ($compareFrom as $mKey => $mValue) {
            if (array_key_exists($mKey, $compareAgainst)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = $this->recursiveDiff($mValue, $compareAgainst[$mKey]);
                    if (count($aRecursiveDiff)) {
                        $out[$mKey] = $aRecursiveDiff;
                    }
                } else {
                    if ($mValue != $compareAgainst[$mKey]) {
                        $out[$mKey] = $mValue;
                    }
                }
            } else {
                $out[$mKey] = $mValue;
            }
        }

        return $out;
    }
}
