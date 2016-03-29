<?php

namespace NS\ImportBundle\Filter;

use \Ddeboer\DataImport\Exception\UnexpectedValueException;
use \Ddeboer\DataImport\ReporterInterface;
use \Doctrine\Common\Collections\ArrayCollection;
use \NS\ImportBundle\Exceptions\InvalidEncodingException;
use \Symfony\Component\HttpFoundation\File\File;

/**
 * Description of Duplicate
 *
 * @author gnat
 */
class Duplicate implements ReporterInterface
{
    private $items;

    private $fields;

    private $duplicates;

    private $message;

    private $logFile;

    private $severity = ReporterInterface::ERROR;

    private $initialized = false;

    /**
     * @param array $fields
     * @param File $logFile
     */
    public function __construct(array $fields = array(), File $logFile = null)
    {
        $this->initialized = false;
        $this->fields      = $fields;
        $this->logFile     = $logFile;
    }

    /**
     *
     * @param array $item
     * @return string
     * @throws UnexpectedValueException
     */
    public function getFieldKey(array $item)
    {
        $fieldKey = null;
        foreach ($this->fields as $method => $field) {
            if (!isset($item[$field])) {
                throw new UnexpectedValueException(sprintf("Field: '%s' doesn't exist!", $field));
            }

            if (is_object($item[$field]) && $method && method_exists($item[$field], $method)) {
                $fieldKey .= sprintf('%s_', strtoupper($item[$field]->$method()));
            } else {
                $value = $this->cleanValues($item[$field]);
                $fieldKey .= sprintf('%s_', $value);
            }
        }

        return mb_substr($fieldKey, 0, -1);
    }

    public function cleanValues($input)
    {
        if (is_array($input)) {
            $output = '';
            foreach ($input as $value) {
                $output .= sprintf('%s_', $this->cleanValues($value));
            }

            return $output;
        }

        if (!mb_check_encoding($input, 'UTF-8')) {
            throw new InvalidEncodingException(sprintf('Invalid UTF-8 key value %s', $value));
        }

        return mb_strtoupper($input);
    }

    /**
     * Filter input
     *
     * @param array $item Input
     *
     * @return boolean If false is returned, the workflow will skip the input
     */
    public function __invoke(array $item)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        $this->message = null;

        $field = $this->getFieldKey($item);

        if (!$this->items->contains($field)) {
            $this->items->add($field);

            return true;
        }

        $this->message = sprintf('Duplicate row detected with key \'%s\'', $field);

        $this->duplicates->add($field);

        return false;
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
     * @return array
     */
    public function toArray()
    {
        return $this->duplicates->toArray();
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return \NS\ImportBundle\Filter\Duplicate
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
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
     */
    public function initialize()
    {
        $items = $duplicates = null;
        if ($this->logFile) {
            list($items, $duplicates) = json_decode(file_get_contents($this->logFile->getPathname()), true);
        }
        $items = $items === null ? array() : $items;
        $duplicates = $duplicates === null ? array() : $duplicates;

        $this->items = new ArrayCollection($items);
        $this->duplicates = new ArrayCollection($duplicates);
        $this->initialized = true;
    }

    /**
     *
     */
    public function finish()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        if ($this->logFile) {
            file_put_contents($this->logFile->getPathname(), json_encode(array(
                $this->items->toArray(),
                $this->duplicates->toArray())
            ));
        }
    }
}
