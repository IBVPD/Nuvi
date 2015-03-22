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

    /**
     *
     * @param string $class
     * @throws RuntimeException
     */
    public function __construct($class)
    {
        if(!class_exists($class))
            throw new RuntimeException(sprintf("Unable to find class %s",$class));

        $this->class = $class;
        $this->name  = join('', array_slice(explode('\\', $class), -1));
    }

    /**
     *
     * @param mixed $input
     * @return object 
     * @throws UnexpectedValueException
     */
    public function convert($input)
    {
        $input = ($input == 98) ? 99 : $input;

        try
        {
            return new $this->class((int)$input);
        }
        catch (UnexpectedValueException2 $ex)
        {
            throw new UnexpectedValueException(sprintf("Unable to convert value '%s' for %s",$input,$this->name), null, $ex);
        }
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
