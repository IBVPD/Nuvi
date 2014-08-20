<?php

namespace NS\ImportBundle\Writer;

use Ddeboer\DataImport\Result as BaseResult;

/**
 * Description of Result
 *
 * @author gnat
 */
class Result extends BaseResult
{
    private $results;

    public function __construct($name, \DateTime $startTime, \DateTime $endTime, $totalCount, array $exceptions = array())
    {
        parent::__construct($name, $startTime, $endTime, $totalCount,$exceptions);

        $this->results = array();
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setResults($results = array())
    {
        $this->results = $results;
        return $this;
    }

    public function setExceptions($exceptions = array())
    {
        $this->exceptions = $exceptions;
    }
}
