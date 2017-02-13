<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/04/16
 * Time: 3:58 PM
 */

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome;
use NS\SentinelBundle\Report\Result\AbstractGeneralStatisticResult;

class GeneralStatisticResult extends AbstractGeneralStatisticResult
{
    /**
     * @param array $dischargeOutcomeDistribution
     * @return \NS\SentinelBundle\Report\Result\RotaVirus\GeneralStatisticResult
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
