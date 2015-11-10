<?php

namespace NS\ImportBundle\Services;

use \Doctrine\ORM\Mapping\ClassMetadata;
use \NS\ImportBundle\Entity\Column;
use \NS\ImportBundle\Entity\Map;
use \NS\ImportBundle\Reader\ReaderFactory;
use \Symfony\Component\Form\AbstractType;

/**
 * Description of MapBuilder
 *
 * @author gnat
 */
class MapBuilder
{
    /**
     * @var AbstractType
     */
    private $converterRegistry;

    /**
     * @var ClassMetaData
     */
    private $metaData;

    /**
     * @var ClassMetaData
     */
    private $siteMetaData;

    /**
     * @var ClassMetaData
     */
    private $nlMetaData;

    /**
     *
     * @param AbstractType $converterRegistry
     * @return \NS\ImportBundle\Services\MapBuilder
     */
    public function setConverterRegistry(AbstractType $converterRegistry)
    {
        $this->converterRegistry = $converterRegistry;
        return $this;
    }

    /**
     *
     * @param ClassMetadata $metaData
     * @return \NS\ImportBundle\Services\MapBuilder
     */
    public function setMetaData(ClassMetadata $metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }

    /**
     * @param ClassMetadata $siteMetaData
     * @return \NS\ImportBundle\Services\MapBuilder
     */
    public function setSiteMetaData(ClassMetadata $siteMetaData)
    {
        $this->siteMetaData = $siteMetaData;
        return $this;
    }

    /**
     * @param mixed $nlMetaData
     * @return MapBuilder
     */
    public function setNlMetaData(ClassMetadata $nlMetaData)
    {
        $this->nlMetaData = $nlMetaData;
        return $this;
    }

    /**
     * @param Map $map
     */
    public function process(Map $map)
    {
        if(!$this->metaData || !$this->siteMetaData || !$this->nlMetaData) {
            throw new \InvalidArgumentException('Missing either class, site, national or reference lab metadata');
        }

        try {
            $reader = ReaderFactory::getReader($map->getFile());
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        $reader->setHeaderRowNumber(0);
        $headers = $reader->getColumnHeaders();

        foreach ($headers as $name) {
            $column = new Column();

            $column->setName($name);
            $column->setMap($map);
            $this->updateMapper($column, $name);

            $map->addColumn($column);
        }
    }

    /**
     * @param Column $column
     * @param string $fieldName
     * @param object $target
     * @return null
     */
    public function updateMapper(Column $column, $fieldName)
    {
        $field = $this->camelCase($fieldName);

        if (in_array($field,$this->metaData->getFieldNames())) {
            $column->setMapper($field);
            $column->setConverter($this->converterRegistry->getConverterForField($this->metaData->getTypeOfField($field)));
            return;
        }
        elseif ($field == 'site') {
            $column->setMapper($field);
            $column->setConverter($this->converterRegistry->getConverterForField($field));
            return;
        }
        elseif(in_array($field,$this->siteMetaData->getFieldNames())) {
            $column->setMapper(sprintf('siteLab.%s',$field));
            $column->setConverter($this->converterRegistry->getConverterForField($this->siteMetaData->getTypeOfField($field)));
            return;
        }
        elseif(in_array($field,$this->nlMetaData->getFieldNames())) {
            $column->setMapper(sprintf('nationalLab.%s',$field));
            $column->setConverter($this->converterRegistry->getConverterForField($this->nlMetaData->getTypeOfField($field)));
            return;
        }

        $column->setIgnored(true);
        return;
    }

    /**
     * @param string $input
     * @return string
     */
    public function camelCase($input)
    {
        if (empty($input)) {
            return $input;
        }

        $output = preg_replace("/[^A-Za-z0-9 ]/", ' ', strtolower($input));

        return str_replace(' ', '', lcfirst(ucwords($output)));
    }
}
