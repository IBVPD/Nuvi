<?php

namespace NS\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Column
 *
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="import_columns")
 */
class Column
{
    /**
     * @var integer id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Map $map
     * @ORM\ManyToOne(targetEntity="Map",inversedBy="columns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $map;

    /**
     * @var string name
     * @ORM\Column(name="name",type="string")
     */
    private $name;

    /**
     * @var string $type
     * @ORM\Column(name="type",type="string")
     */
    private $type;

    /**
     * @var integer $type
     * @ORM\Column(name="orderCol",type="integer")
     */
    private $order;

    /**
     * @var array $valueMap
     * @ORM\Column(name="valueMap",type="array")
     */
    private $valueMap;

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s-%s (%s)",$this->name,$this->type,$this->order);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function getValueMap()
    {
        return $this->valueMap;
    }

    public function setValueMap($valueMap)
    {
        if(!is_array($valueMap))
        {
            $rows = explode(',', $valueMap);
            foreach($rows as &$r)
                $r = explode('=',$r);

            $valueMap = $rows;
        }

        $this->valueMap = $valueMap;

        return $this;
    }

    public function getConverter()
    {
        return $this->converter;
    }

    public function setConverter(Converter $converter)
    {
        $this->converter = $converter;
        return $this;
    }

    public function setMap(Map $map)
    {
        $this->map = $map;
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

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function convert($data)
    {
        if($this->type == 'string' && !is_string($data))
            throw new \UnexpectedValueException("$data is not of type string");

        if($this->type == 'integer' && !is_numeric($data))
            throw new \UnexpectedValueException("$data is not numeric");

        if(isset($this->valueMap[$data]))
            return $this->valueMap[$data];

        throw new \UnexpectedValueException("There is no result for $data");
    }
}