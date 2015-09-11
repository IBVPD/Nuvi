<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 11/09/15
 * Time: 4:38 PM
 */

namespace NS\SentinelBundle\Listeners;


use NS\SentinelBundle\Entity\BaseCase;

class RotaVirusListener extends BaseCaseListener
{
    /**
     *
     */
    public function calculateResult(BaseCase $case)
    {
        return null;
    }

    /**
     * @return null
     */
    public function getIncompleteField(BaseCase $case)
    {
        return null;
    }

    /**
     * @return array
     */
    public function getMinimumRequiredFields(BaseCase $case)
    {
        return array(
            'caseId',
            'dob',
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
            'symptomDehydrationAmount',
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
        );
    }
}
