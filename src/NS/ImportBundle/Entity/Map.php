<?php

namespace NS\ImportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use NS\ImportBundle\Converter\Expression\Condition;
use NS\ImportBundle\Converter\Expression\ExpressionBuilder;
use NS\ImportBundle\Converter\MappingItemConverter;
use NS\ImportBundle\Converter\PreprocessorStep;
use NS\ImportBundle\Converter\UnsetMappingItemConverter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of Map
 *
 * @author gnat
 * @ORM\Entity(repositoryClass="\NS\ImportBundle\Repository\MapRepository")
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
     * @var string $description
     * @ORM\Column(name="description",type="text",nullable=true)
     */
    private $description;

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

    /**
     * @var
     */
    private $file;

    /**
     * @var Collection $columns
     * @ORM\OneToMany(targetEntity="Column",mappedBy="map", fetch="EAGER",cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"order" = "ASC"})
     */
    private $columns;

    /**
     * {@inheritdoc}
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
        }

        if ($this->columns) {
            $columns = array();

            foreach ($this->columns as $col) {
                $columns[] = clone $col;
            }

            $this->columns = $columns;
        }
    }

    /**
     *
     * @return \NS\ImportBundle\Entity\Map
     */
    public function __construct()
    {
        $this->columns = new ArrayCollection();

        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name . ' ' . $this->version;
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return Collection
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $description
     * @return \NS\ImportBundle\Entity\Map
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     *
     * @param UploadedFile $file
     * @return \NS\ImportBundle\Entity\Map
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     *
     * @param string $class
     * @return \NS\ImportBundle\Entity\Map
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     *
     * @param string $version
     * @return \NS\ImportBundle\Entity\Map
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     *
     * @param string $name
     * @return \NS\ImportBundle\Entity\Map
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @param Collection $columns
     * @return \NS\ImportBundle\Entity\Map
     */
    public function setColumns(Collection $columns)
    {
        foreach ($columns as $c) {
            $c->setMap($this);
        }

        $this->columns = $columns;

        return $this;
    }

    /**
     *
     * @param \NS\ImportBundle\Entity\Column $column
     * @return \NS\ImportBundle\Entity\Map
     */
    public function addColumn(Column $column)
    {
        $column->setMap($this);

        $this->columns->add($column);

        return $this;
    }

    /**
     *
     * @param \NS\ImportBundle\Entity\Column $column
     * @return \NS\ImportBundle\Entity\Map
     */
    public function removeColumn(Column $column)
    {
        $column->setMap(null);

        $this->columns->remove($column);

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getColumnHeaders()
    {
        $headers = array();

        foreach ($this->columns as $col) {
            $headers[] = $col->getName();
        }

        return $headers;
    }

    /**
     *
     * @return array
     */
    public function getConverters()
    {
        $r = array();
        foreach ($this->columns as $col) {
            if ($col->hasConverter()) {
                $r[] = $col;
            }
        }

        return $r;
    }

    /**
     *
     * @return MappingItemConverter
     */
    public function getMappings()
    {
        $mappings = array();

        foreach ($this->columns as $col) {
            if ($col->hasMapper()) {
                $name = $this->adjustMappingName($col->getName());
                $target = $this->adjustMappingTarget($col->getMapper());
                $mappings[$name] = $target;
            }
        }

        return new \Ddeboer\DataImport\Step\MappingStep($mappings);
    }

    /**
     *
     * @return UnsetMappingItemConverter
     */
    public function getIgnoredMapper()
    {
        $mappings = array();

        foreach ($this->columns as $col) {
            if ($col->isIgnored()) {
                $name = $this->adjustMappingName($col->getName());
                $target = $this->adjustMappingTarget($col->getMapper());
                $mappings[$name] = $target;
            }
        }

        return new UnsetMappingItemConverter($mappings);

    }

    public function getPreProcessor()
    {
        $processor = new PreprocessorStep(new ExpressionBuilder());
        $haveConditions = false;

        foreach($this->columns as $col) {
            if($col->hasPreProcessor()) {
                $haveConditions = true;
                $conditions     = array();
                $preConditions  = json_decode($col->getPreProcessor(),true);

                foreach($preConditions as $json) {
                    $conditions[] = new Condition($json['conditions'],$json['output_value']);
                }

                $processor->add($col->getName(),$conditions);
            }
        }

        return ($haveConditions) ? $processor:null;
    }

    /**
     * @param string $name
     * @return string
     */
    public function adjustMappingName($name)
    {
        return sprintf("[%s]", $name);
    }

    /**
     * @param string $name
     * @return string
     */
    public function adjustMappingTarget($name)
    {
        return sprintf("[%s]", str_replace('.', '][', $name));
    }
}