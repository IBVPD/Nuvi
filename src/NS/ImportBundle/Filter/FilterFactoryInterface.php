<?php

namespace NS\ImportBundle\Filter;

/**
 *
 * @author gnat
 */
interface FilterFactoryInterface
{
    public function createFilter($className);

    public function setFilterClass($className);
}