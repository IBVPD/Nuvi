<?php

namespace NS\SentinelBundle\Entity\Listener;

use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Pneumonia;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;

class PneumoniaListener extends BaseCaseListener
{
    /**
     * Suspected: 0-59 months, with fever, one of the following: stiff neck, altered conciousness and no other sign
     *              OR
     *            Every patient 0-59 months hospitalized with clinical diagnosis of meningitis
     *
     * Probable: Suspected + CSF examination as one of the following
     *              - Turbid appearance
     *              - Leukocytosis ( > 100 cells/mm3)
     *              - Leukocytosis ( 10-100 cells/mm3) AND either elevated protein (> 100mg/dl) or decreased glucose (<
     *              40 mg/dl)
     *
     * Confirmed: Suspected + culture or (Gram stain, antigen detection, immunochromotagraphy, PCR or other methods)
     *            a bacterial pathogen (Hib, pneumococcus or meningococcus) in the CSF or from the blood in a child
     *            with a clinical syndrome consistent with bacterial meningitis
     *
     * @param Pneumonia\Pneumonia|BaseCase $case
     *
     * @return void
     */
    public function calculateResult(BaseCase $case): void
    {
    }

    /**
     * @param Pneumonia\Pneumonia|BaseCase $case
     *
     * @return bool
     */
    public function isSuspected(BaseCase $case): ?bool
    {
        // Test Suspected
        if (($case->getAge() < 60) && $case->getAdmDx() && $case->getAdmDx()->equal(Diagnosis::SUSPECTED_MENINGITIS)) {
            $case->getResult()->setValue(CaseResult::SUSPECTED);
            return true;
        }

        return false;
    }
}
