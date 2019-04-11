<?php

namespace NS\SentinelBundle\Entity\Listener;

use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\IBD\Types\CSFAppearance;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\UtilBundle\Form\Types\ArrayChoice;

class IBDListener extends BaseCaseListener
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
     * @param IBD|BaseCase $case
     * @return mixed|void
     */
    public function calculateResult(BaseCase $case)
    {
        if ($case->getStatus()->getValue() >= CaseStatus::CANCELLED) {
            return;
        }

        if($case instanceof Pneumonia) {
            return;
        }

        if ($this->isSuspected($case)) {
            // Probable
            if ($case->getCsfAppearance() && $case->getCsfAppearance()->equal(CSFAppearance::TURBID)) {
                $case->getResult()->setValue(CaseResult::PROBABLE);
            } else {
                if ($case->getSiteLab()) {
                    /** @var IBD\SiteLab $lab */
                    $lab = $case->getSiteLab();
                    if (($lab->getCsfWcc() > 10 && $lab->getCsfWcc() <= 100) && (($lab->getCsfGlucose() >= 0 && $lab->getCsfGlucose() < 40) || ($lab->getCsfProtein() > 100))) {
                        $case->getResult()->setValue(CaseResult::PROBABLE);
                    } else {
                        $case->getResult()->setValue(CaseResult::CONFIRMED);
                    }
                }
            } // Confirmed
        }
    }

    public function isSuspected(BaseCase $case): bool
    {
        // Test Suspected
        if ($case->getAge() < 60) {
            if ($case instanceof Meningitis && $case->getMenFever() && $case->getMenFever()->equal(TripleChoice::YES)) {
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

    /**
     * @param BaseCase $case
     * @return null|string
     */
    public function getIncompleteField(BaseCase $case): ?string
    {
        foreach ($this->getMinimumRequiredFields($case) as $field) {
            $method = sprintf('get%s', $field);
            $value  = call_user_func([$case, $method]);

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

        if ($case->getCsfCollected() && $case->getCsfCollected()->equal(TripleChoice::YES)) {

            if ($case->getCsfCollectDate() === null) {
                return 'csfCollectDate';
            }

            if ($case->getCsfCollectTime() === null) {
                return 'csfCollectTime';
            }

            if ($case->getCsfAppearance() === null) {
                return 'csfAppearance1';
            }

            if ($case->getCsfAppearance()->equal(ArrayChoice::NO_SELECTION)) {
                return 'csfAppearance2';
            }
        }

        if ($case->getOtherSpecimenCollected() && $case->getOtherSpecimenCollected()->equal(OtherSpecimen::OTHER) && !$case->getOtherSpecimenOther()) {
            return 'otherSpecimenOther';
        }

        return null;
    }

    public function getMinimumRequiredFields(BaseCase $case, $regionCode = null): array
    {
        $fields = [
            'caseId',
            'birthdate',
            'gender',
            'district',
            'admDate',
            'onsetDate',
            'admDx',
            'antibiotics',
            'menSeizures',
            'menFever',
            'menAltConscious',
            'menInabilityFeed',
            'menNeckStiff',
            'menRash',
            'menFontanelleBulge',
            'menLethargy',
            'hibReceived',
            'pcvReceived',
            'meningReceived',
            'csfCollected',
            'bloodCollected',
            'otherSpecimenCollected',
            'dischOutcome',
            'dischDx',
            'dischClass',
            'cxrDone',
        ];

        return (!$case->getCountry() || ($case->getCountry() && $case->getCountry()->getTracksPneumonia())) ? array_merge($fields, $this->getPneumiaRequiredFields()) : $fields;
    }

    public function getPneumiaRequiredFields(): array
    {
        return ['pneuDiffBreathe',
            'pneuChestIndraw',
            'pneuCough',
            'pneuCyanosis',
            'pneuStridor',
            'pneuRespRate',
            'pneuVomit',
            'pneuHypothermia',
            'pneuMalnutrition',];
    }
}
