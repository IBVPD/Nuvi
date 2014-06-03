<?php

namespace NS\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Converter
 *
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="import_value_converter")
 */
class Converter
{
    /**
     * @var integer id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $type
     * @ORM\Column(name="type",type="string")
     */
    private $type;

    /**
     * @var array
     * @ORM\Column(name="valueMap",type="array")
     */
    private $valueMap;

    public function getId()
    {
        return $this->id;
    }

    public function getValueMap()
    {
        return $this->valueMap;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setValueMap($valueMap)
    {
        $this->valueMap = $valueMap;
        return $this;
    }

    public function setColumns(Array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function addColumn(Column $col)
    {
        $col->setConverter($this);
        $this->columns->add($col);

        return $this;
    }

    public function removeColumn(Column $col)
    {
        $col->setConverter(null);
        $this->columns->remove($col);

        return $this;
    }

    public function convert($data)
    {
        if(isset($this->valueMap[$data]))
            return $this->valueMap[$data];

        throw new \UnexpectedValueException("There is no result for $data");
    }
}