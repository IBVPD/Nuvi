<?php

namespace NS\ImportBundle\Converter;

use Ddeboer\DataImport\ReporterInterface;

class FieldCombinerConverter implements ReporterInterface
{
    /**
     * @var
     */
    private $message;

    /**
     * @var string
     */
    protected $sourceField;
    /**
     * @var string
     */
    protected $destinationField;

    /**
     * FieldCombinerConverter constructor.
     * @param $sourceField string
     * @param $destinationField string
     */
    public function __construct($sourceField, $destinationField)
    {
        $this->sourceField = $sourceField;
        $this->destinationField = $destinationField;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function __invoke($item)
    {
        $this->message = null;

        if (!isset($item[$this->sourceField])) {
            $this->message = sprintf('Unable to find %s source field', $this->sourceField);
            return $item;
        }

        if (!isset($item[$this->destinationField])) {
            $this->message = sprintf('Unable to find %s destination field', $this->destinationField);
            return $item;
        }

        $source = &$item[$this->sourceField];
        $dest = &$item[$this->destinationField];

        if (gettype($source) !== gettype($dest)) {
            $this->message = sprintf('Mismatched types source: %s != dest: %s', gettype($source), gettype($dest));
            return $item;
        }

        if (empty($source)) {
            return $item;
        }

        if (is_numeric($source)) {
            $dest = round($dest+$source,10);
        } elseif (is_string($source)) {
            $dest = sprintf('%s %s', $dest, $source);
        } else {
            $this->message = sprintf('Expected string or number, got %s instead', gettype($source));
        }

        return $item;
    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        return (!empty($this->message));
    }

    /**
     * @return mixed
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
        return ReporterInterface::ERROR;
    }
}
