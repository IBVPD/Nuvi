<?php

namespace NS\SentinelBundle\Converter;

use \Ddeboer\DataImport\Exception\UnexpectedValueException;
use \Ddeboer\DataImport\ReporterInterface;
use \NS\ImportBundle\Converter\NamedValueConverterInterface;
use \UnexpectedValueException as UnexpectedValueException2;

/**
 * Description of ArrayChoiceConverter
 *
 * @author gnat
 */
class ArrayChoiceConverter implements NamedValueConverterInterface, ReporterInterface
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
     * @var string
     */
    private $message = null;

    private $severity = ReporterInterface::INFO;

    /**
     * @param string $class
     * @throws RuntimeException
     */
    public function __construct($class)
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf("Unable to find class %s", $class));
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
                $this->message = sprintf('%s is invalid - setting to out of range value',$value);
                return new $this->class($cons);
            }

            throw new UnexpectedValueException(sprintf("Unable to convert value '%s' for %s", $input, $this->name), null, $ex);
        }
    }

    /**
     * @return boolean
     */
    public function hasMessage()
    {
        return ($this->message === null);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getSeverity()
    {
        return $this->severity;
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
