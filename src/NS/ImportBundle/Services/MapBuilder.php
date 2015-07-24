<?php

namespace NS\ImportBundle\Services;

use \Ddeboer\DataImport\Reader\CsvReader;
use \Doctrine\ORM\Mapping\ClassMetadata;
use \NS\ImportBundle\Entity\Column;
use \NS\ImportBundle\Entity\Map;
use \Symfony\Component\Form\AbstractType;

/**
 * Description of MapBuilder
 *
 * @author gnat
 */
class MapBuilder
{
    private $converterRegistry;
    private $metaData;
    private $siteMetaData;
    private $externLabMetaData;

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
     * @param ClassMetadata $externLabMetaData
     * @return \NS\ImportBundle\Services\MapBuilder
     */
    public function setExternLabMetaData(ClassMetadata $externLabMetaData)
    {
        $this->externLabMetaData = $externLabMetaData;
        return $this;
    }

        /**
     *
     * @param Map $map
     * @param ClassMetadata $metaData
     */
    public function process(Map $map)
    {
        if(!$this->metaData || !$this->siteMetaData || !$this->externLabMetaData) {
            throw new \InvalidArgumentException("Missing either class, site lab or external lab metadata");
        }

        $csvReader = new CsvReader($map->getFile()->openFile());
        $csvReader->setHeaderRowNumber(0);
        $headers   = $csvReader->getColumnHeaders();

        foreach ($headers as $index => $name) {
            $column = new Column();

            $column->setName($name);
            $column->setOrder($index);
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
        elseif(in_array($field,$this->externLabMetaData->getFieldNames())) {
            $column->setMapper(sprintf('nationalLab.%s',$field));
            $column->setConverter($this->converterRegistry->getConverterForField($this->externLabMetaData->getTypeOfField($field)));
            return;
        }

        if($field == 'sfCultOther') {
            print $field;
            die;
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
