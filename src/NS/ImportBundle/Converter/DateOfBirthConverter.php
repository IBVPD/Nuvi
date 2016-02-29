<?php

namespace NS\ImportBundle\Converter;


use Ddeboer\DataImport\ReporterInterface;

class DateOfBirthConverter implements ReporterInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * @inheritDoc
     */
    public function __invoke($item)
    {
        $this->message = null;

        if (isset($item['dob']) && $item['dob'] instanceof \DateTime && isset($item['admDate']) && $item['admDate'] instanceof \DateTime) {
            if ($item['dob'] > $item['admDate']) {
                $item['warning'] = true;
                $this->message = 'Admission date is before dob';
            }
        }

        return $item;
    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        return ($this->message !== null);
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
        return ReporterInterface::WARNING;
    }
}
