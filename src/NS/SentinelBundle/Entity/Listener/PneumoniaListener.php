<?php

namespace NS\SentinelBundle\Entity\Listener;

use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Pneumonia;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\UtilBundle\Form\Types\ArrayChoice;

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
     *              - Leukocytosis ( 10-100 cells/mm3) AND either elevated protein (> 100mg/dl) or decreased glucose (< 40 mg/dl)
     *
     * Confirmed: Suspected + culture or (Gram stain, antigen detection, immunochromotagraphy, PCR or other methods)
     *            a bacterial pathogen (Hib, pneumococcus or meningococcus) in the CSF or from the blood in a child with a clinical
     *            syndrome consistent with bacterial meningitis
     *
     * @param Pneumonia\Pneumonia|BaseCase $case
     * @return mixed|void
     */
    public function calculateResult(BaseCase $case)
    {
        return;
    }

    /**
     * @param Pneumonia\Pneumonia|BaseCase $case
     * @return bool
     */
    public function isSuspected(BaseCase $case)
    {
        // Test Suspected
        if ($case->getAge() < 60) {
            if ($case->getAdmDx() && $case->getAdmDx()->equal(Diagnosis::SUSPECTED_MENINGITIS)) {
                $case->getResult()->setValue(CaseResult::SUSPECTED);
                return true;
            }
        }

        return false;
    }

    /**
     * @param Pneumonia\Pneumonia|BaseCase $case
     * @return null|string
     */
    public function getIncompleteField(BaseCase $case)
    {
        foreach ($this->getMinimumRequiredFields($case) as $field) {
            $method = sprintf('get%s', $field);
            $value = call_user_func([$case, $method]);

            if ($value === null || empty($value) || ($value instanceof ArrayChoice && $value->equal(-1))) {
                return $field;
            }
        }

        // this isn't covered by the above loop because its valid for age == 0 but 0 == empty
        if ($case->getAge() === null) {
            return 'age';
        }

        if ($case->getAdmDx() && $case->getAdmDx()->equal(Diagnosis::OTHER) && !$case->getAdmDxOther()) {
            return 'admDx';
        }

        if ($case->getDischDx() && $case->getDischDx()->equal(DischargeDiagnosis::OTHER) && !$case->getDischDxOther()) {
            return 'dischDx';
        }

        if ($case->getHibReceived() && ($case->getHibReceived()->equal(VaccinationReceived::YES_HISTORY) || $case->getHibReceived()->equal(VaccinationReceived::YES_CARD)) && ($case->getHibDoses() === null || $case->getHibDoses()->equal(ArrayChoice::NO_SELECTION))) {
            return 'hibReceived';
        }

        if ($case->getPcvReceived() && ($case->getPcvReceived()->equal(VaccinationReceived::YES_HISTORY) || $case->getPcvReceived()->equal(VaccinationReceived::YES_CARD)) && ($case->getPcvDoses() === null || $case->getPcvDoses()->equal(ArrayChoice::NO_SELECTION))) {
            return 'pcvReceived';
        }

        if ($case->getCxrDone() && $case->getCxrDone()->equal(TripleChoice::YES) && ($case->getCxrResult() === null || $case->getCxrResult()->equal(ArrayChoice::NO_SELECTION))) {
            return 'cxrDone';
        }

        if ($case->getMeningReceived() && ($case->getMeningReceived()->equal(VaccinationReceived::YES_CARD) || $case->getMeningReceived()->equal(VaccinationReceived::YES_HISTORY))) {
            if ($case->getMeningType() === null) {
                return 'meningType1';
            }

            if ($case->getMeningType()->equal(ArrayChoice::NO_SELECTION)) {
                return 'meningType2';
            }

            if ($case->getMeningDate() === null) {
                return 'meningDate';
            }
        }

        if ($case->getOtherSpecimenCollected() && $case->getOtherSpecimenCollected()->equal(OtherSpecimen::OTHER) && !$case->getOtherSpecimenOther()) {
            return 'otherSpecimenOther';
        }

        return null;
    }

    /**
     * @param BaseCase $case
     * @return array
     */
    public function getMinimumRequiredFields(BaseCase $case)
    {
        return [
            'caseId',
            'birthdate',
            'gender',
            'district',
            'admDate',
            'onsetDate',
            'admDx',
            'antibiotics',
            'hibReceived',
            'pcvReceived',
            'meningReceived',
            'bloodCollected',
            'otherSpecimenCollected',
            'dischOutcome',
            'dischDx',
            'dischClass',
            'cxrDone',
            'pneuDiffBreathe',
            'pneuChestIndraw',
            'pneuCough',
            'pneuCyanosis',
            'pneuStridor',
            'pneuRespRate',
            'pneuVomit',
            'pneuHypothermia',
            'pneuMalnutrition'
        ];
    }
}