<?php

namespace NS\SentinelBundle\Reports;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Collector
 *
 * @author gnat
 */
class Hash extends ArrayCollection
{
    private $nodes = array();

    public function add($key, $value)
    {
        if(!isset($this->nodes[$key]))
            $this->nodes[$key] = new Node();

        $this->nodes[$key]->add($value);
    }

    public function get($key)
    {
        if(isset($this->nodes[$key]))
            return $this->nodes[$key];

        return null;
    }
}
