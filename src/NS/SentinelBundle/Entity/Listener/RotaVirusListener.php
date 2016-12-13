<?php

namespace NS\SentinelBundle\Entity\Listener;

use NS\SentinelBundle\Entity\BaseCase;

class RotaVirusListener extends BaseCaseListener
{
    /**
     * @param BaseCase $case
     * @return mixed|null
     */
    public function calculateResult(BaseCase $case)
    {
        return null;
    }

    /**
     * @param BaseCase $case
     * @return null
     */
    public function getIncompleteField(BaseCase $case)
    {
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
            'symptomDiarrhea',
            'symptomDiarrheaOnset',
            'symptomDiarrheaEpisodes',
            'symptomDiarrheaDuration',
            'symptomVomit',
            'symptomVomitEpisodes',
            'symptomVomitDuration',
            'symptomDehydration',
            'rehydration',
            'rehydrationType',
            'rehydrationOther',
            'vaccinationReceived',
            'vaccinationType',
            'doses',
            'firstVaccinationDose',
            'secondVaccinationDose',
            'thirdVaccinationDose',
            'stoolCollected',
            'stoolId',
            'stoolCollectionDate',
            'dischargeOutcome',
            'dischargeDate',
            'dischargeClassOther',
            'comment',
        ];
    }
}
