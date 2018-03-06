<?php

namespace NS\SentinelBundle\DataFixtures\Provider;

use NS\SentinelBundle\Form\Meningitis\Types\CSFAppearance;
use NS\SentinelBundle\Form\Meningitis\Types\Diagnosis;
use NS\SentinelBundle\Form\Meningitis\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\Meningitis\Types\DischargeOutcome;

class MeningitisProvider
{
    public function csfAppearance()
    {
        $choices = [
            new CSFAppearance(CSFAppearance::CLEAR),
            new CSFAppearance(CSFAppearance::TURBID),
            new CSFAppearance(CSFAppearance::BLOODY),
            new CSFAppearance(CSFAppearance::XANTHROCHROMIC),
            new CSFAppearance(CSFAppearance::OTHER),
            new CSFAppearance(CSFAppearance::NOT_ASSESSED),
            new CSFAppearance(CSFAppearance::UNKNOWN),
        ];

        return $choices[array_rand($choices)];
    }
}
