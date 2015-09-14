<?php

namespace NS\SentinelBundle\Listeners;

use \NS\SentinelBundle\Entity\BaseCase;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Form\Types\CaseStatus;
use \NS\SentinelBundle\Form\Types\CSFAppearance;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\IBDCaseResult;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use NS\SentinelBundle\Form\Types\OtherSpecimen;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use \NS\UtilBundle\Form\Types\ArrayChoice;

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
     *              - Leukocytosis ( 10-100 cells/mm3) AND either elevated protein (> 100mg/dl) or decreased glucose (< 400 mg/dl)
     *
     * Confirmed: Suspected + culture or (Gram stain, antigen detection, immunochromotagraphy, PCR or other methods)
     *            a bacterial pathogen (Hib, pneumococcus or meningococcus) in the CSF or from the blood in a child with a clinical
     *            syndrome consistent with bacterial meningitis
     *
     */
    public function calculateResult(BaseCase $case)
    {
        if($case->getStatus()->getValue() >= CaseStatus::CANCELLED) {
            return;
        }

        if($this->isSuspected($case))
        {
            // Probable
            if ($case->getCsfAppearance() && $case->getCsfAppearance()->equal(CSFAppearance::TURBID)) {
                $case->getResult()->setValue(IBDCaseResult::PROBABLE);
            } else {
                if ($case->getSiteLab()) {
                    $lab = $case->getSiteLab();
                    if (($lab->getCsfWcc() > 10 && $lab->getCsfWcc() <= 100) && (($lab->getCsfGlucose() >= 0 && $lab->getCsfGlucose() < 40) || ($lab->getCsfProtein() > 100))) {
                        $case->getResult()->setValue(IBDCaseResult::PROBABLE);
                    } else {
                        $case->getResult()->setValue(IBDCaseResult::CONFIRMED);
                    }
                }
            } // Confirmed
        }
    }

    public function isSuspected(IBD $case)
    {
        // Test Suspected
        if($case->getAge() < 60)
        {
            if ($case->getMenFever() && $case->getMenFever()->equal(TripleChoice::YES)) {
                if (($case->getMenAltConscious() && $case->getMenAltConscious()->equal(TripleChoice::YES)) || ($case->getMenNeckStiff() && $case->getMenNeckStiff()->equal(TripleChoice::YES))) {
                    $case->getResult()->setValue(IBDCaseResult::SUSPECTED);
                    return true;
                }
            } elseif ($case->getAdmDx() && $case->getAdmDx()->equal(Diagnosis::SUSPECTED_MENINGITIS)) {
                $case->getResult()->setValue(IBDCaseResult::SUSPECTED);
                return true;
            }
        }

        return false;
    }

    /**
     * @return null|string
     */
    public function getIncompleteField(BaseCase $case)
    {
        foreach ($this->getMinimumRequiredFields($case) as $field) {
            $method = sprintf('get%s',$field);
            $value  = call_user_func(array($case,$method));

            if (is_null($value) || empty($value) || ($value instanceof ArrayChoice && $value->equal(-1))) {
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

        if ($case->getDischDx() && $case->getDischDx()->equal(Diagnosis::OTHER) && !$case->getDischDxOther()) {
            return 'dischDx';
        }

        if ($case->getHibReceived() && ($case->getHibReceived()->equal(VaccinationReceived::YES_HISTORY) || $case->getHibReceived()->equal(VaccinationReceived::YES_CARD)) && (is_null($case->getHibDoses()) || $case->getHibDoses()->equal(ArrayChoice::NO_SELECTION))) {
            return 'hibReceived';
        }

        if ($case->getPcvReceived() && ($case->getPcvReceived()->equal(VaccinationReceived::YES_HISTORY) || $case->getPcvReceived()->equal(VaccinationReceived::YES_CARD)) && (is_null($case->getPcvDoses()) || $case->getPcvDoses()->equal(ArrayChoice::NO_SELECTION))) {
            return 'pcvReceived';
        }

        if ($case->getCxrDone() && $case->getCxrDone()->equal(TripleChoice::YES) && (is_null($case->getCxrResult()) || $case->getCxrResult()->equal(ArrayChoice::NO_SELECTION))) {
            return 'cxrDone';
        }

        if ($case->getMeningReceived() && ($case->getMeningReceived()->equal(MeningitisVaccinationReceived::YES_CARD) || $case->getMeningReceived()->equal(MeningitisVaccinationReceived::YES_HISTORY))) {
            if (is_null($case->getMeningType())) {
                return 'meningType1';
            }

            if ($case->getMeningType()->equal(ArrayChoice::NO_SELECTION)) {
                return 'meningType2';
            }

            if (is_null($case->getMeningMostRecentDose())) {
                return 'meningMostRecentDose';
            }
        }

        if ($case->getCsfCollected() && $case->getCsfCollected()->equal(TripleChoice::YES)) {

            if (is_null($case->getCsfCollectDateTime())) {
                return 'csfCollectDateTime';
            }

            if (is_null($case->getCsfAppearance())) {
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

    /**
     * @return array
     */
    public function getMinimumRequiredFields(BaseCase $case)
    {
        $fields = array(
            'caseId',
            'dob',
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
        );

        return (!$case->getCountry() || ($case->getCountry() && $case->getCountry()->getTracksPneumonia())) ? array_merge($fields,$this->getPneumiaRequiredFields()) : $fields;
    }

    /**
     * @return array
     */
    public function getPneumiaRequiredFields()
    {
        return array('pneuDiffBreathe',
            'pneuChestIndraw',
            'pneuCough',
            'pneuCyanosis',
            'pneuStridor',
            'pneuRespRate',
            'pneuVomit',
            'pneuHypothermia',
            'pneuMalnutrition',);
    }
}
