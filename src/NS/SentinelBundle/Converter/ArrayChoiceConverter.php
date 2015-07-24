<?php

namespace NS\SentinelBundle\Converter;

use Ddeboer\DataImport\Exception\UnexpectedValueException;
use NS\ImportBundle\Converter\NamedValueConverterInterface;
use NS\UtilBundle\Validator\Constraints\ArrayChoice;
use RuntimeException;
use UnexpectedValueException as UnexpectedValueException2;

/**
 * Description of ArrayChoiceConverter
 *
 * @author gnat
 */
class ArrayChoiceConverter implements NamedValueConverterInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $class
     * @throws RuntimeException
     */
    public function __construct($class)
    {
        if (!class_exists($class)) {
            throw new RuntimeException(sprintf("Unable to find class %s", $class));
        }

        $this->class = $class;
        $this->name  = join('', array_slice(explode('\\', $class), -1));
    }

    /**
     *
     * @param mixed $value
     * @return object 
     * @throws UnexpectedValueException
     */
    public function __invoke($value)
    {
        $input = (is_string($value)) ? trim($value) : $value;
        if ($input !== 0 && (empty($input) || !is_numeric($input))) {
            if (is_string($input) && strpos($input, "=")) {
                $values = explode("=", $input);
                $input  = trim($values[0]);
            }
            else {
                return new $this->class();
            }
        }

        try {
            return new $this->class( is_numeric($input) ? (int) $input : $input );
        }
        catch (UnexpectedValueException2 $ex) {
            $cons = constant(sprintf('%s::OUT_OF_RANGE',$this->class));

            if($cons) {
                return new $this->class($cons);
            }

            throw new UnexpectedValueException(sprintf("Unable to convert value '%s' for %s", $input, $this->name), null, $ex);
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
