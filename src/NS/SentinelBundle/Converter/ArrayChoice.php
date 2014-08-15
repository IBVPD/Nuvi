<?php

namespace NS\SentinelBundle\Converter;

use \NS\ImportBundle\Converter\NamedValueConverterInterface;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class ArrayChoice implements NamedValueConverterInterface
{
    private $class;
    private $name;

    public function __construct($class)
    {
        if(!class_exists($class))
            throw new \RuntimeException(sprintf("Unable to find class %s",$class));

        $this->class = $class;
        $this->name  = join('', array_slice(explode('\\', $class), -1));
    }

    public function convert($input)
    {
        return (!empty($input) ? new $this->class($input): null);
    }

    public function getName()
    {
        return $this->name;
    }
}
