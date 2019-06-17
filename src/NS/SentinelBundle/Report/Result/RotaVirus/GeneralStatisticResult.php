<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome;
use NS\SentinelBundle\Report\Result\AbstractGeneralStatisticResult;

class GeneralStatisticResult extends AbstractGeneralStatisticResult
{
    public function setDischargeOutcomeDistribution(array $dischargeOutcomeDistribution): void
    {
        $outcome = new DischargeOutcome();
        foreach ($dischargeOutcomeDistribution as $result) {
            $outcome->setValue($result['outcome']);
            $this->dischargeOutcomeDistribution[$outcome->__toString()] = $result['caseCount'];
        }
   }
}
