<?php

namespace NS\ImportBundle\Converter;

use Ddeboer\DataImport\ReporterInterface;

/**
 * Class DateRangeConverter
 * @package NS\ImportBundle\Converter
 */
class DateRangeConverter implements ReporterInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var \DateTime
     */
    private $lessThanDate;

    /**
     * @var \DateTime
     */
    private $greaterThanDate;

    /**
     * @var bool
     */
    private $warningOnly = false;

    /**
     * NoFutureDateConverter constructor.
     * @param \DateTime $lessThanDate
     * @param \DateTime $greaterThanDate
     * @param bool $warningOnly
     */
    public function __construct(\DateTime $lessThanDate = null, \DateTime $greaterThanDate = null, $warningOnly = false)
    {
        $this->lessThanDate = $lessThanDate;
        $this->greaterThanDate = $greaterThanDate;
        $this->warningOnly = $warningOnly;
    }

    /**
     * @inheritDoc
     */
    public function __invoke($item)
    {
        $this->message = null;

        $data = $this->findDate($item);
        if ($this->hasMessage()) {
            $data['warning'] = true;
        }

        return $data;
    }

    /**
     * @param $item
     * @param null $parent
     * @return mixed
     */
    public function findDate($item, $parent = null)
    {
        foreach ($item as $key => $value) {
            if (is_array($value)) {
                $item[$key] = $this->findDate($value, $this->getKey($key, $parent));
            } elseif ($value instanceof \DateTime) {
                if (!$this->inRange($value, $this->getKey($key, $parent))) {
                    $this->handleNotInRange($item[$key]);//$item[$key] = null;
                }
            }
        }

        return $item;
    }

    /**
     * @param $item
     */
    public function handleNotInRange(&$item)
    {
        if (!$this->warningOnly) {
            $item = null;
        }
    }

    /**
     * @param \DateTime $value
     * @param $key
     * @return bool
     */
    public function inRange(\DateTime $value, $key)
    {
        if ($this->lessThanDate && $this->greaterThanDate) {
            if ($value > $this->lessThanDate || $value < $this->greaterThanDate) {
                $this->message .= sprintf('[%s] has a date (%s) outside acceptable range (%s - %s). ', $key, $value->format('Y-m-d'), $this->greaterThanDate->format('Y-m-d'), $this->lessThanDate->format('Y-m-d'));
                return false;
            }
        } elseif ($this->lessThanDate && $value > $this->lessThanDate) {
            $this->message .= sprintf('[%s] has a date (%s) greater than (%s). ', $key, $value->format('Y-m-d'), $this->lessThanDate->format('Y-m-d'));
            return false;
        } elseif ($this->greaterThanDate && $value < $this->greaterThanDate) {
            $this->message .= sprintf('[%s] has a date (%s) less than (%s). ', $key, $value->format('Y-m-d'), $this->greaterThanDate->format('Y-m-d'));
            return false;
        }

        return true;
    }

    /**
     * @param \DateTime $lessThanDate
     * @return DateRangeConverter
     */
    public function setLessThanDate($lessThanDate)
    {
        $this->lessThanDate = $lessThanDate;
        return $this;
    }

    /**
     * @param \DateTime $greaterThanDate
     * @return DateRangeConverter
     */
    public function setGreaterThanDate($greaterThanDate)
    {
        $this->greaterThanDate = $greaterThanDate;
        return $this;
    }

    /**
     * @param $key
     * @param null $parent
     * @return string
     */
    public function getKey($key, $parent = null)
    {
        return ($parent) ? "$parent.$key" : $key;
    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        return !empty($this->message);
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
        return ReporterInterface::WARNING;
    }
}
