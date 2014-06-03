<?php

namespace NS\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Map
 *
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="import_map")
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
     *
     * @var Array $columns
     * @ORM\OneToMany(targetEntity="Column",mappedBy="map", fetch="EAGER",cascade={"persist"}, orphanRemoval=true)
     */
    private $columns;

    public function __construct()
    {
        $this->columns = new ArrayCollection();
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
        die("HERE ".__LINE__);
        foreach($columns as $c)
            $c->setMap($this);

        $this->columns = $columns;
        return $this;
    }

    public function addColumn(Column $column)
    {
        die("HERE ".__LINE__);
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

    public function getValueForColumn($columnKey,$data)
    {
        foreach($this->columns as $col)
        {
            if($col->getOrder() == $columnKey)
            {
                return $col->convert($data);
            }
        }

        throw new \RuntimeException();
    }
}