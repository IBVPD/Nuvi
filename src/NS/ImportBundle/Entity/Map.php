<?php

namespace NS\ImportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use NS\ImportBundle\Converter\MappingItemConverter;
use NS\ImportBundle\Converter\UnsetMappingItemConverter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of Map
 *
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="import_map")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Map
{
    /**
     * @var integer id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string name
     * @ORM\Column(name="name",type="string")
     */
    private $name;

    /**
     * @var string version
     * @ORM\Column(name="version",type="string")
     */
    private $version;

    /**
     * @var string $class
     * @ORM\Column(name="class",type="string")
     */
    private $class;

    private $file;
    /**
     * @var Array $columns
     * @ORM\OneToMany(targetEntity="Column",mappedBy="map", fetch="EAGER",cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"order" = "ASC"})
     */
    private $columns;

    public function __clone()
    {
        if($this->id)
            $this->id = null;

        if($this->columns)
        {
            $columns = array();

            foreach($this->columns as $col)
                $columns[] = clone $col;

            $this->columns = $columns;
        }
    }

    public function __construct()
    {
        $this->columns = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name.' '.$this->version;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        return $this;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setColumns(Array $columns)
    {
        foreach($columns as $c)
            $c->setMap($this);

        $this->columns = $columns;

        return $this;
    }

    public function addColumn(Column $column)
    {
        $column->setMap($this);

        $this->columns->add($column);

        return $this;
    }

    public function removeColumn(Column $column)
    {
        $column->setMap(null);

        $this->columns->remove($column);

        return $this;
    }

    public function getColumnHeaders()
    {
        $headers = array();

        foreach($this->columns as $col)
            $headers[] = $col->getName();

        return $headers;
    }

    public function getConverters()
    {
        $r = array();
        foreach($this->columns as $col)
        {
            if($col->hasConverter())
                $r[] = $col;
        }

        return $r;
    }

    public function getMappings()
    {
        $r = new MappingItemConverter();

        foreach($this->columns as $col)
        {
            if($col->hasMapper())
                $r->addMapping($col->getName(), $col->getMapper());
        }

        return $r;
    }

    public function getIgnoredMapper()
    {
        $r = new UnsetMappingItemConverter();
        foreach($this->columns as $col)
        {
            if($col->getIsIgnored())
                $r->addMapping($col->getName(), $col->getMapper());
        }

        return $r;
    }
}