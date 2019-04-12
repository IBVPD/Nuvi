<?php

namespace NS\SentinelBundle\Entity\Listener;

use NS\SentinelBundle\Entity\BaseCase;

class RotaVirusListener extends BaseCaseListener
{
    public function calculateResult(BaseCase $case): void
    {
    }

    public function getIncompleteField(BaseCase $case): ?string
    {
        return null;
    }

    public function getMinimumRequiredFields(BaseCase $case, ?string $regionCode = null): array
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
