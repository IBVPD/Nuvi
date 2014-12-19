<?php

namespace NS\ImportBundle\Entity;

/**
 * Description of Import
 *
 * @author gnat
 */
class Import
{
    private $map;
    private $file;

    public function getMap()
    {
        return $this->map;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setMap($map)
    {
        $this->map = $map;
        return $this;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    // Pass through functions

    public function getConverters()
    {
        return $this->map->getConverters();
    }

    public function getMappings()
    {
        return $this->map->getMappings();
    }

    public function getIgnoredMapper()
    {
        return $this->map->getIgnoredMapper();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->map->getClass();
    }
}
