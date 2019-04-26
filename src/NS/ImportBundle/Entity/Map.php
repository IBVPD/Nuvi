<?php

namespace NS\ImportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use NS\ImportBundle\Converter\Expression\Condition;
use NS\ImportBundle\Validators as LocalAssert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Map
 *
 * @author gnat
 * @ORM\Entity(repositoryClass="\NS\ImportBundle\Repository\MapRepository")
 * @ORM\Table(name="import_map")
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @LocalAssert\ImportMap()
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
     * @Assert\NotBlank()
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
     * @Assert\NotBlank()
     */
    private $class;

    /**
     * @var int
     * @ORM\Column(name="headerRow",type="integer")
     * @Assert\GreaterThan(value="0",message="This must be greater than zero")
     * @Assert\NotBlank()
     */
    private $headerRow = 1;

    /**
     * @var string
     * @ORM\Column(name="caseLinker",type="string",nullable=true)
     * @Assert\NotBlank()
     */
    private $caseLinker;

    /**
     * @var
     * @Assert\File
     */
    private $file;

    /**
     * @var Collection $columns
     * @ORM\OneToMany(targetEntity="Column",mappedBy="map", fetch="EAGER",cascade={"persist"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $columns;

    /**
     * @var string
     * @Assert\Choice(choices={"referenceLab","nationalLab"})
     */
    private $labPreference = 'referenceLab';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active = true;

    /**
     * {@inheritdoc}
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
        }

        if ($this->columns) {
            $columns = [];

            foreach ($this->columns as $col) {
                $columns[] = clone $col;
            }

            $this->columns = $columns;
        }
    }

    public function __construct()
    {
        $this->columns = new ArrayCollection();
    }

    /**
     * @return bool|null
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     */
    public function setActive($active)
    {
        $this->active = (bool)$active;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s (%s %s)', $this->name, $this->getSimpleClass(), $this->version);
    }

    /**
     * @return string
     */
    public function getSelectName()
    {
        $name = $this->__toString();
        return (mb_strlen($name) <= 30) ? $name: sprintf('%s (%s: %s...)', $this->name, $this->getSimpleClass(), mb_substr($this->version, 0, 27-mb_strlen($this->name)));
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
     * @return Collection|Column[]
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

    public function getSimpleClass()
    {
        return substr($this->class, strrpos($this->class, '\\')+1);
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
     * @return int
     */
    public function getHeaderRow()
    {
        return $this->headerRow;
    }

    /**
     * @param int $headerRow
     * @return Map
     */
    public function setHeaderRow($headerRow)
    {
        $this->headerRow = $headerRow;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabPreference()
    {
        return $this->labPreference;
    }

    /**
     * @param string $labPreference
     * @return Map
     */
    public function setLabPreference($labPreference)
    {
        $this->labPreference = $labPreference;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaseLinker()
    {
        return $this->caseLinker;
    }

    /**
     * @param string $caseLinker
     * @return Map
     */
    public function setCaseLinker($caseLinker)
    {
        $this->caseLinker = $caseLinker;
        return $this;
    }

    //----------------------------------------------
    /**
     *
     * @return array
     */
    public function getColumnHeaders()
    {
        $headers = [];

        foreach ($this->columns as $col) {
            $headers[] = $col->getName();
        }

        return $headers;
    }

    /**
     *
     * @return array|Column[]
     */
    public function getConvertedColumns()
    {
        $r = [];
        foreach ($this->columns as $col) {
            if ($col->hasConverter()) {
                $r[] = $col;
            }
        }

        return $r;
    }

    /**
     * @return array|Column[]
     */
    public function getMappedColumns()
    {
        $mappings = [];

        foreach ($this->columns as $col) {
            if ($col->hasMapper()) {
                $name = $this->adjustMappingName($col->getName());
                $target = $this->adjustMappingTarget($col->getMapper());
                $mappings[$name] = $target;
            }
        }

        return $mappings;
    }

    /**
     * @return array|Column[]
     */
    public function getIgnoredColumns()
    {
        $mappings = [];

        foreach ($this->columns as $col) {
            if ($col->isIgnored()) {
                $mappings[] = $col->getName();
            }
        }

        return $mappings;
    }

    /**
     * @return array|Column[]
     */
    public function getPreProcessorConditions()
    {
        $allConditions = [];

        foreach ($this->columns as $col) {
            if ($col->hasPreProcessor()) {
                $conditions     = [];
                $preConditions  = json_decode($col->getPreProcessor(), true);

                foreach ($preConditions as $json) {
                    $conditions[] = new Condition($json['conditions'], $json['output_value']);
                }

                $allConditions[$col->getName()] = $conditions;
            }
        }

        return $allConditions;
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
