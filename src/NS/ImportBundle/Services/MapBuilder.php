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

    public function setConverterRegistry(AbstractType $converterRegistry)
    {
        $this->converterRegistry = $converterRegistry;
        return $this;
    }

    public function process(Map $map, ClassMetadata $metaData)
    {
        $csvReader = new CsvReader($map->getFile()->openFile());
        $csvReader->setHeaderRowNumber(0);

        $headers     = $csvReader->getColumnHeaders();
        $targetClass = $map->getClass();
        $target      = new $targetClass;

        foreach ($headers as $index => $name)
        {
            $c = new Column();

            $c->setName($name);
            $c->setOrder($index);
            $c->setMap($map);

            $temp   = $this->camelCase($name);
            $method = sprintf('get%s', $temp);
            if (method_exists($target, $method))
            {
                $c->setMapper($temp);
                try
                {
                    $c->setConverter($this->converterRegistry->getConverterForField($metaData->getFieldMapping($temp)));
                }
                catch (MappingException $e)
                {

                }
            }
            else
                $c->setIgnored(true);

            $map->addColumn($c);
        }
    }

    public function camelCase($input)
    {
        if (empty($input))
            return $input;

        $output = preg_replace("/[^A-Za-z0-9 ]/", ' ', strtolower($input));

        return str_replace(' ', '', lcfirst(ucwords($output)));
    }
}