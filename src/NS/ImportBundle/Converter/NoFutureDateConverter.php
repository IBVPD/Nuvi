<?php

namespace NS\ImportBundle\Converter;

use Ddeboer\DataImport\ReporterInterface;
use Ddeboer\DataImport\Step\ConverterStep;

/**
 * Class NoFutureDateConverter
 * @package NS\ImportBundle\Converter
 */
class NoFutureDateConverter extends ConverterStep implements ReporterInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var int
     */
    private $severity;

    private $today;

    /**
     * NoFutureDateConverter constructor.
     */
    public function __construct()
    {
        $this->today = new \DateTime();
    }


    /**
     * @inheritDoc
     */
    public function __invoke($item)
    {
        $this->message = null;

        $data = $this->findDate($item);
        if($this->hasMessage()) {
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
        foreach($item as $key => $value) {
            if(is_array($value)) {
                $item[$key] = $this->findDate($value, $this->getKey($key,$parent));
            } elseif ($value instanceof \DateTime && $value > $this->today) {
                $this->message .= sprintf('[%s] has a date in the future (%s). ', $this->getKey($key,$parent), $value->format('Y-m-d'));
                $item[$key] = null;
            }
        }

        return $item;
    }

    /**
     * @param $key
     * @param null $parent
     * @return string
     */
    public function getKey($key, $parent = null)
    {
        return ($parent) ? "$parent.$key":$key;
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