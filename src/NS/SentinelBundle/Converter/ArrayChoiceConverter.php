<?php

namespace NS\SentinelBundle\Converter;

use Ddeboer\DataImport\Exception\UnexpectedValueException;
use Ddeboer\DataImport\ReporterInterface;
use NS\ImportBundle\Converter\NamedValueConverterInterface;
use UnexpectedValueException as UnexpectedValueException2;

/**
 * Description of ArrayChoiceConverter
 *
 * @author gnat
 */
class ArrayChoiceConverter implements NamedValueConverterInterface, ReporterInterface
{
    /** @var string */
    private $class;

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var string */
    private $message = null;

    private $severity = ReporterInterface::WARNING;

    /**
     * @param string $class
     * @param string $type
     * @throws \RuntimeException
     */
    public function __construct(string $class, string $type)
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Unable to find class %s', $class));
        }

        $this->class = $class;
        $this->type = implode('', array_slice(explode('\\', $class), -1));
        $this->name  = sprintf('%s (%s)', $this->type, $type);
    }

    /**
     *
     * @param mixed $value
     * @return object 
     * @throws UnexpectedValueException
     */
    public function __invoke($value)
    {
        $this->message = null;

        $input = \is_string($value) ? trim($value) : $value;
        if ($input !== 0 && $input != '0' && empty($input)) {
            return new $this->class();
        }

        try {
            return new $this->class(is_numeric($input) ? (int) $input : $input);
        } catch (UnexpectedValueException2 $ex) {
            $cons = \constant(sprintf('%s::OUT_OF_RANGE', $this->class));

            $this->message = sprintf('Invalid value (out of range). Set to %s to %d', $value, $cons);
            return new $this->class($cons);
        }
    }

    /**
     * @return boolean
     */
    public function hasMessage()
    {
        return ($this->message !== null);
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
