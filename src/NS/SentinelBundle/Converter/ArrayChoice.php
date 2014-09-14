<?php

namespace NS\SentinelBundle\Converter;

use Ddeboer\DataImport\Exception\UnexpectedValueException;
use NS\ImportBundle\Converter\NamedValueConverterInterface;
use RuntimeException;
use UnexpectedValueException as UnexpectedValueException2;

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
            throw new RuntimeException(sprintf("Unable to find class %s",$class));

        $this->class = $class;
        $this->name  = join('', array_slice(explode('\\', $class), -1));
    }

    public function convert($input)
    {
        try
        {
            return (!empty($input) ? new $this->class((int)$input): null);
        }
        catch (UnexpectedValueException2 $ex)
        {
            throw new UnexpectedValueException(sprintf("Unable to convert value '%s' for %s",$input,join('', array_slice(explode('\\', $this->class), -1))), null, $ex);
        }
    }

    public function getName()
    {
        return $this->name;
    }
}
