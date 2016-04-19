<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/04/16
 * Time: 12:21 PM
 */

namespace NS\SentinelBundle\Report\Result\IBD;


use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Report\Result\AbstractGeneralStatisticResult;

class GeneralStatisticResult extends AbstractGeneralStatisticResult
{
    /**
     * @param array $dischargeOutcomeDistribution
     * @return GeneralStatisticResult
     */
    public function setDischargeOutcomeDistribution($dischargeOutcomeDistribution)
    {
        $outcome = new DischargeOutcome();
        foreach ($dischargeOutcomeDistribution as $result) {
            $outcome->setValue($result['outcome']);
            $this->dischargeOutcomeDistribution[$outcome->__toString()] = $result['caseCount'];
        }

        return $this;
    }
}
