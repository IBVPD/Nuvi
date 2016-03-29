<?php

namespace NS\ImportBundle\Services;

use \Doctrine\ORM\Mapping\ClassMetadata;
use \NS\ImportBundle\Converter\Registry;
use \NS\ImportBundle\Entity\Column;
use \NS\ImportBundle\Entity\Map;
use \NS\ImportBundle\Reader\ReaderFactory;

/**
 * Description of MapBuilder
 *
 * @author gnat
 */
class MapBuilder
{
    /**
     * @var ReaderFactory
     */
    private $readerFactory;

    /**
     * @var Registry
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
     * MapBuilder constructor.
     * @param ReaderFactory $readerFactory
     */
    public function __construct(ReaderFactory $readerFactory)
    {
        $this->readerFactory = $readerFactory;
    }

    /**
     * @return mixed
     */
    public function getReaderFactory()
    {
        return $this->readerFactory;
    }

    /**
     * @param ReaderFactory $readerFactory
     * @return MapBuilder
     */
    public function setReaderFactory(ReaderFactory $readerFactory)
    {
        $this->readerFactory = $readerFactory;
        return $this;
    }

    /**
     *
     * @param Registry $converterRegistry
     * @return \NS\ImportBundle\Services\MapBuilder
     */
    public function setConverterRegistry(Registry $converterRegistry)
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
        if (!$this->metaData || !$this->siteMetaData || !$this->nlMetaData) {
            throw new \InvalidArgumentException('Missing either class, site, national or reference lab metadata');
        }

        try {
            $reader = $this->readerFactory->getReader($map->getFile());
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        $reader->setHeaderRowNumber($map->getHeaderRow()-1);
        $headers = $reader->getColumnHeaders();

        foreach ($headers as $name) {
            $column = new Column();

            $column->setName($name);
            $column->setMap($map);
            $this->updateMapper($column, $name, $map->getLabPreference());

            $map->addColumn($column);
        }
    }

    /**
     * @param Column $column
     * @param string $fieldName
     * @param string $labPreference
     */
    public function updateMapper(Column $column, $fieldName, $labPreference = 'referenceLab')
    {
        $field = $this->camelCase($fieldName);

        if (in_array($field, $this->metaData->getFieldNames())) {
            $column->setMapper($field);
            $column->setConverter($this->converterRegistry->getConverterForField($this->metaData->getTypeOfField($field)));
            return;
        } elseif ($field == 'site') {
            $column->setMapper($field);
            $column->setConverter($this->converterRegistry->getConverterForField($field));
            return;
        } elseif (in_array($field, $this->siteMetaData->getFieldNames())) {
            $column->setMapper(sprintf('siteLab.%s', $field));
            $column->setConverter($this->converterRegistry->getConverterForField($this->siteMetaData->getTypeOfField($field)));
            return;
        } elseif (in_array($field, $this->nlMetaData->getFieldNames())) {
            $column->setMapper(sprintf('%s.%s', $labPreference, $field));
            $column->setConverter($this->converterRegistry->getConverterForField($this->nlMetaData->getTypeOfField($field)));
            return;
        }

        $column->setIgnored(true);
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
