<?php

namespace NS\SentinelBundle\Entity\Listener;

use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Meningitis;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\CSFAppearance;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\UtilBundle\Form\Types\ArrayChoice;

class MeningitisListener extends BaseCaseListener
{
    /**
     * Suspected: 0-59 months, with fever, one of the following: stiff neck, altered conciousness and no other sign
     *              OR
     *            Every patient 0-59 months hospitalized with clinical diagnosis of meningitis
     *
     * Probable: Suspected + CSF examination as one of the following
     *              - Turbid appearance
     *              - Leukocytosis ( > 100 cells/mm3)
     *              - Leukocytosis ( 10-100 cells/mm3) AND either elevated protein (> 100mg/dl) or decreased glucose (< 40 mg/dl)
     *
     * Confirmed: Suspected + culture or (Gram stain, antigen detection, immunochromotagraphy, PCR or other methods)
     *            a bacterial pathogen (Hib, pneumococcus or meningococcus) in the CSF or from the blood in a child with a clinical
     *            syndrome consistent with bacterial meningitis
     * @param Meningitis\Meningitis|BaseCase $case
     * @return mixed|void
     */
    public function calculateResult(BaseCase $case): void
    {
        if ($case->getStatus()->getValue() >= CaseStatus::CANCELLED) {
            return;
        }

        if (!$case instanceof Meningitis\Meningitis) {
            return;
        }

        if ($this->isSuspected($case)) {
            // Probable
            if ($case->getCsfAppearance() && $case->getCsfAppearance()->equal(CSFAppearance::TURBID)) {
                $case->getResult()->setValue(CaseResult::PROBABLE);
            } elseif ($case->getSiteLab()) {
                /** @var Meningitis\SiteLab $lab */
                $lab = $case->getSiteLab();
                if (($lab->getCsfWcc() > 10 && $lab->getCsfWcc() <= 100) && (($lab->getCsfGlucose() >= 0 && $lab->getCsfGlucose() < 40) || ($lab->getCsfProtein() > 100))) {
                    $case->getResult()->setValue(CaseResult::PROBABLE);
                } else {
                    $case->getResult()->setValue(CaseResult::CONFIRMED);
                }
            } // Confirmed
        }
    }

    public function isSuspected(BaseCase $case): bool
    {
        // Test Suspected
        if ($case->getAge() < 60) {
            if ($case instanceof Meningitis\Meningitis && $case->getMenFever() && $case->getMenFever()->equal(TripleChoice::YES)) {
                if (($case->getMenAltConscious() && $case->getMenAltConscious()->equal(TripleChoice::YES)) || ($case->getMenNeckStiff() && $case->getMenNeckStiff()->equal(TripleChoice::YES))) {
                    $case->getResult()->setValue(CaseResult::SUSPECTED);
                    return true;
                }
            } elseif ($case->getAdmDx() && $case->getAdmDx()->equal(Diagnosis::SUSPECTED_MENINGITIS)) {
                $case->getResult()->setValue(CaseResult::SUSPECTED);
                return true;
            }
        }

        return false;
    }
}
