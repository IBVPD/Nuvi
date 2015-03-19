<?php

namespace NS\ImportBundle\Entity;

/**
 * Description of Import
 *
 * @author gnat
 */
class Import
{
    /**
     * @var Map
     */
    private $map;

    /**
     * @var File
     */
    private $file;

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param Map $map
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setMap(Map $map)
    {
        $this->map = $map;
        return $this;
    }

    /**
     *
     * @param File $file
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    // Pass through functions

    /**
     * @return array
     */
    public function getConverters()
    {
        return $this->map->getConverters();
    }

    /**
     * @return array
     */
    public function getMappings()
    {
        return $this->map->getMappings();
    }

    /**
     * @return array
     */
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
