<?php

namespace NS\SentinelBundle\Result;

use \NS\SentinelBundle\Form\Types\Diagnosis;

/**
 * Description of NumberEnrolledResult
 *
 * @author gnat
 */
class NumberEnrolledResult
{
    private $diagnosis;
    private $dValues;
    private $resultByMonth;
    private $headers;

    public function __construct()
    {
        $this->diagnosis     = new Diagnosis();
        $this->dValues       = $this->diagnosis->getValues();
        $this->empty         = array_fill_keys(array_keys($this->dValues),0);
        $this->resultByMonth = array_fill(1,12,$this->empty);
        $this->headers       = array_merge(array('month'=>'Month'),$this->dValues);
    }

    public function load($inputResults)
    {
        foreach($inputResults as $res)
            $this->resultByMonth[$res['AdmissionMonth']][$res['admDx']->getValue()] = $res['admDxCount'];
    }

    public function getHeaders($asStrings  = false)
    {
        if($asStrings)
            return array_flip($this->headers);

        return $this->headers;
    }

    public function all()
    {
        return $this->resultByMonth;
    }

    public function getMonthResult($month)
    {
        return (isset($this->resultByMonth[$month]))? $this->resultByMonth[$month]: $this->empty;
    }
}
