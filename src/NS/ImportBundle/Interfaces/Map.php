<?php

namespace NS\ImportBundle\Interfaces;

/**
 *
 * @author gnat
 */
interface Map
{
    public function getColumns();
    public function getColumn($key);
    public function getValueForColumn($columnKey,$data);
}