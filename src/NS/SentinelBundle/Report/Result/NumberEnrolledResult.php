<?php

namespace NS\SentinelBundle\Report\Result;

use \NS\SentinelBundle\Form\IBD\Types\Diagnosis;

/**
 * Description of NumberEnrolledResult
 *
 * @author gnat
 */
class NumberEnrolledResult
{
    /**
     * @var array
     */
    private $dValues;

    /**
     * @var array
     */
    private $resultByMonth;

    /**
     * @var array
     */
    private $headers;

    public function __construct()
    {
        $diagnosis     = new Diagnosis();
        $this->dValues = $diagnosis->getValues();
        $this->empty   = array_fill_keys(array_keys($this->dValues), 0);

        // Special values (NOT_SET and OUT_OF_RANGE)
        $this->empty[-1]     = 0;
        $this->empty[-9999]  = 0;

        $this->resultByMonth = array_fill(1, 12, $this->empty);
        $this->headers       = array_merge(array('month'=>'Month'), $this->dValues,array(-1=>'Not set',-9999=>'Out of Range'));
    }

    /**
     * @param $inputResults
     */
    public function load($inputResults)
    {
        foreach ($inputResults as $res) {
            $this->resultByMonth[$res['AdmissionMonth']][$res['adm_dx']->getValue()] = $res['admDxCount'];
        }
    }

    /**
     * @param bool|false $asStrings
     * @return array
     */
    public function getHeaders($asStrings  = false)
    {
        if ($asStrings) {
            return array_flip($this->headers);
        }

        return $this->headers;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->resultByMonth;
    }

    /**
     * @param $month
     * @return array
     */
    public function getMonthResult($month)
    {
        return (isset($this->resultByMonth[$month]))? $this->resultByMonth[$month]: $this->empty;
    }
}
