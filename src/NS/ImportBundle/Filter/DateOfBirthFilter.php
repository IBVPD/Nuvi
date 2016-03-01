<?php

namespace NS\ImportBundle\Filter;

use Ddeboer\DataImport\ReporterInterface;

/**
 * Class DateOfBirthFilter
 * @package NS\ImportBundle\Filter
 */
class DateOfBirthFilter implements ReporterInterface
{
    /**
     * @var
     */
    private $message;

    /**
     * @inheritDoc
     *
     * @return boolean If false is returned, the workflow will skip the input
     */
    public function __invoke($item)
    {
        $this->message = null;

        if(isset($item['birthdate']) && $item['birthdate'] instanceof \DateTime && isset($item['admDate']) && $item['admDate'] instanceof \DateTime) {
            if($item['birthdate'] > $item['admDate']) {
                $this->message = 'Admission date is before birthdate';

                return false;
            }
        }

        return true;
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
