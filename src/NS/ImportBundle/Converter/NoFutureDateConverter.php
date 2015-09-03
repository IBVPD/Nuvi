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

    /**
     * @inheritDoc
     */
    public function __invoke($item)
    {
        $today = new \DateTime();

        foreach($item as $key=>$value) {
            if($value instanceof \DateTime && $value > $today) {
                $this->message .= sprintf('[%s] has a date in the future (%s). ',$key,$value->format('Y-m-d'));
                $item[$key] = null;
                $item['warning'] = true;
            }
        }

        return $item;
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