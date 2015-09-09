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
     * @var string $preProcessor
     * @ORM\Column(name="preProcessor",type="string",nullable=true, length=4096)
     */
    private $preProcessor;

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @return bool
     */
    public function hasPreProcessor()
    {
        return !empty($this->preProcessor);
    }

    /**
     * @return string
     */
    public function getPreProcessor()
    {
        return $this->preProcessor;
    }

    /**
     * @param string $preProcessor
     * @return Column
     */
    public function setPreProcessor($preProcessor)
    {
        $this->preProcessor = $preProcessor;
        return $this;
    }

    /**
     * @return string
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * @return bool
     */
    public function hasConverter()
    {
        return ($this->converter) ? true : false;
    }

    /**
     * @return string
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @return bool
     */
    public function hasMapper()
    {
        return (!empty($this->mapper));
    }

    /**
     * @return bool
     */
    public function isIgnored()
    {
        return $this->ignored;
    }

    /**
     * @param $isIgnored
     * @return $this
     */
    public function setIgnored($isIgnored)
    {
        $this->ignored = $isIgnored;
        return $this;
    }

    /**
     * @param $mapper
     * @return $this
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * @param $converter
     * @return $this
     */
    public function setConverter($converter)
    {
        $this->converter = $converter;

        return $this;
    }

    /**
     * @param Map $map
     * @return $this
     */
    public function setMap(Map $map)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}