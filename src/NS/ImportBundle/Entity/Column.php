<?php

namespace NS\ImportBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Description of Column
 *
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="import_columns")
 * @SuppressWarnings(PHPMD.ShortVariable)
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
     * @var integer $type
     * @ORM\Column(name="orderCol",type="integer")
     */
    private $order;

    /**
     * @var string $converter
     * @ORM\Column(name="converter",type="string",nullable=true)
     */
    private $converter;

    /**
     * @var string $mapper
     * @ORM\Column(name="mapper",type="string",nullable=true)
     */
    private $mapper;

    /**
     * @var boolean $ignored
     * @ORM\Column(name="ignored",type="boolean",nullable=true)
     */
    private $ignored = false;

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s-%s (%s)", $this->name, $this->type, $this->order);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function getConverter()
    {
        return $this->converter;
    }

    public function hasConverter()
    {
        return ($this->converter)?true:false;
    }

    public function getMapper()
    {
        return $this->mapper;
    }

    public function hasMapper()
    {
        return (!empty($this->mapper));
    }

    public function isIgnored()
    {
        return $this->ignored;
    }

    public function setIgnored($isIgnored)
    {
        $this->ignored = $isIgnored;
        return $this;
    }

    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    public function setConverter($converter)
    {
        $this->converter = $converter;

        return $this;
    }

    public function setMap(Map $map)
    {
        $this->map = $map;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}