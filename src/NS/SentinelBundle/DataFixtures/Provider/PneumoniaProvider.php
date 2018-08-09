<?php

namespace NS\SentinelBundle\DataFixtures\Provider;

use NS\SentinelBundle\Form\Pneumonia\Types\CSFAppearance;
use NS\SentinelBundle\Form\Pneumonia\Types\CXRResult;
use NS\SentinelBundle\Form\Pneumonia\Types\Diagnosis;
use NS\SentinelBundle\Form\Pneumonia\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\Pneumonia\Types\DischargeOutcome;

class PneumoniaProvider
{
    /**
     * @return mixed
     */
    public function cxrResult()
    {
        $choices = [
            new CXRResult(CXRResult::CONSISTENT),
            new CXRResult(CXRResult::NORMAL),
            new CXRResult(CXRResult::INCONCLUSIVE),
            new CXRResult(CXRResult::OTHER),
            new CXRResult(CXRResult::UNKNOWN),
        ];

        return $choices[array_rand($choices)];
    }
}
