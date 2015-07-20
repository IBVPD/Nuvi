<?php

namespace NS\ImportBundle\Services;

use \Ddeboer\DataImport\Reader\CsvReader;
use \Doctrine\ORM\Mapping\ClassMetadata;
use \Doctrine\ORM\Mapping\MappingException;
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
     *
     * @param Map $map
     * @param ClassMetadata $metaData
     */
    public function process(Map $map, ClassMetadata $metaData)
    {
        $this->metaData = $metaData;
        $csvReader = new CsvReader($map->getFile()->openFile());
        $csvReader->setHeaderRowNumber(0);

        $headers     = $csvReader->getColumnHeaders();
        $targetClass = $map->getClass();
        $target      = new $targetClass;

        foreach ($headers as $index => $name) {
            $column = new Column();

            $column->setName($name);
            $column->setOrder($index);
            $column->setMap($map);
            $this->updateMapper($column, $name, $target);

            $map->addColumn($column);
        }
    }

    /**
     * @param Column $column
     * @param string $name
     * @param object $target
     * @return null
     */
    public function updateMapper(Column $column, $name, $target)
    {
        $temp   = $this->camelCase($name);
        $method = sprintf('get%s', $temp);
        if (method_exists($target, $method)) {
            $column->setMapper($temp);
            try {
                $column->setConverter($this->converterRegistry->getConverterForField($this->metaData->getFieldMapping($temp)));
                return;
            }
            catch (MappingException $except) {

            }
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
