<?php

namespace NS\ImportBundle\Writer;

use \Ddeboer\DataImport\Result as BaseResult;
use \NS\ImportBundle\Filter\Duplicate;

/**
 * Description of Result
 *
 * @author gnat
 */
class Result extends BaseResult
{
    private $results;
    private $duplicates;

    public function __construct($name, \DateTime $startTime, \DateTime $endTime, $totalCount, Duplicate $duplicates, array $exceptions = array())
    {
        parent::__construct($name, $startTime, $endTime, $totalCount, $exceptions);

        $this->results    = array();
        $this->duplicates = $duplicates;
    }

    /**
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     *
     * @param array $results
     * @return \NS\ImportBundle\Writer\Result
     */
    public function setResults($results = array())
    {
        $this->results = $results;
        return $this;
    }

    /**
     *
     * @param array $exceptions
     */
    public function setExceptions($exceptions = array())
    {
        $this->exceptions = $exceptions;
    }

    /**
     *
     * @return Duplicate
     */
    public function getDuplicates()
    {
        return $this->duplicates;
    }

    /**
     *
     * @param Duplicate $duplicates
     * @return \NS\ImportBundle\Writer\Result
     */
    public function setDuplicates(Duplicate $duplicates)
    {
        $this->duplicates = $duplicates;
        return $this;
    }
}
