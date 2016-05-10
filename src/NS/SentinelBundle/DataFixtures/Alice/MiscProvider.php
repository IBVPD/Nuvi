<?php

namespace NS\SentinelBundle\DataFixtures\Alice;

use \Nelmio\Alice\Fixtures;
use \NS\SentinelBundle\Form\IBD\Types\CSFAppearance;
use \NS\SentinelBundle\Form\IBD\Types\CXRResult;
use \NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use \NS\SentinelBundle\Form\Types\FourDoses;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\Role;
use \NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome as RVDischargeOutcome;
use \NS\SentinelBundle\Form\Types\SurveillanceConducted;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\VaccinationReceived;

class MiscProvider
{
    public function ibdDiagnosis()
    {
        $choices = array(
            new Diagnosis(Diagnosis::OUT_OF_RANGE),
            new Diagnosis(Diagnosis::NO_SELECTION),
            new Diagnosis(Diagnosis::MULTIPLE),
            new Diagnosis(Diagnosis::OTHER),
            new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS),
            new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            new Diagnosis(Diagnosis::SUSPECTED_SEPSIS),
            new Diagnosis(Diagnosis::SUSPECTED_SEVERE_PNEUMONIA),
            new Diagnosis(Diagnosis::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    public function ibdDischargeOutcome()
    {
        $choices = array(
            new DischargeOutcome(DischargeOutcome::OUT_OF_RANGE),
            new DischargeOutcome(DischargeOutcome::NO_SELECTION),
            new DischargeOutcome(DischargeOutcome::DIED),
            new DischargeOutcome(DischargeOutcome::TRANSFERRED),
            new DischargeOutcome(DischargeOutcome::DISCHARGED_ALIVE_WITH_SEQUELAE),
            new DischargeOutcome(DischargeOutcome::DISCHARGED_ALIVE_WITHOUT_SEQUELAE),
            new DischargeOutcome(DischargeOutcome::LEFT_AGAINST_ADVICE),
        );

        return $choices[array_rand($choices)];
    }

    public function ibdDischargeDiagnosis()
    {
        $choices = array(
            new DischargeDiagnosis(DischargeDiagnosis::OUT_OF_RANGE),
            new DischargeDiagnosis(DischargeDiagnosis::NO_SELECTION),
            new DischargeDiagnosis(DischargeDiagnosis::BACTERIAL_MENINGITIS),
            new DischargeDiagnosis(DischargeDiagnosis::BACTERIAL_PNEUMONIA),
            new DischargeDiagnosis(DischargeDiagnosis::MULTIPLE),
            new DischargeDiagnosis(DischargeDiagnosis::OTHER),
            new DischargeDiagnosis(DischargeDiagnosis::SEPSIS),
        );

        return $choices[array_rand($choices)];
    }

    public function rotaDischargeOutcome()
    {
        $choices = array(
            new RVDischargeOutcome(RVDischargeOutcome::DIED),
            new RVDischargeOutcome(RVDischargeOutcome::DISCHARGED_ALIVE),
            new RVDischargeOutcome(RVDischargeOutcome::TRANSFERRED),
            new RVDischargeOutcome(RVDischargeOutcome::LEFT_AGAINST_ADVICE),
            new RVDischargeOutcome(RVDischargeOutcome::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    /**
     * @return SurveillanceConducted
     */
    public function surveillanceConducted()
    {
        return new SurveillanceConducted(SurveillanceConducted::BOTH);
    }

    /**
     * @return mixed
     */
    public function done()
    {
        $choices = array(
            new TripleChoice(TripleChoice::YES),
            new TripleChoice(TripleChoice::NO),
            new TripleChoice(TripleChoice::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    /**
     * @return mixed
     */
    public function gender()
    {
        $choices = array(
            new Gender(Gender::MALE),
            new Gender(Gender::FEMALE),
        );

        return $choices[array_rand($choices)];
    }

    /**
     * @return mixed
     */
    public function diagnosis()
    {
        $choices = array(
            new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS),
            new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            new Diagnosis(Diagnosis::SUSPECTED_SEPSIS),
            new Diagnosis(Diagnosis::OTHER),
        );

        return $choices[array_rand($choices)];
    }

    /**
     * @return mixed
     */
    public function cxrResult()
    {
        $choices = array(
            new CXRResult(CXRResult::CONSISTENT),
            new CXRResult(CXRResult::NORMAL),
            new CXRResult(CXRResult::INCONCLUSIVE),
            new CXRResult(CXRResult::OTHER),
            new CXRResult(CXRResult::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    /**
     * @return mixed
     */
    public function vaccinationReceived()
    {
        $choices = array(
            new VaccinationReceived(VaccinationReceived::NO),
            new VaccinationReceived(VaccinationReceived::YES_HISTORY),
            new VaccinationReceived(VaccinationReceived::YES_CARD),
            new VaccinationReceived(VaccinationReceived::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    /**
     * @return mixed
     */
    public function fourDoses()
    {
        $choices = array(
            new FourDoses(FourDoses::ONE),
            new FourDoses(FourDoses::TWO),
            new FourDoses(FourDoses::THREE),
            new FourDoses(FourDoses::FOUR),
            new FourDoses(FourDoses::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    /**
     * @return mixed
     */
    public function csfAppearance()
    {
        $choices = array(
            new CSFAppearance(CSFAppearance::CLEAR),
            new CSFAppearance(CSFAppearance::TURBID),
            new CSFAppearance(CSFAppearance::BLOODY),
            new CSFAppearance(CSFAppearance::XANTHROCHROMIC),
            new CSFAppearance(CSFAppearance::OTHER),
            new CSFAppearance(CSFAppearance::NOT_ASSESSED),
            new CSFAppearance(CSFAppearance::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    /**
     * @return Role
     */
    public function regionRole()
    {
        return new Role(Role::REGION);
    }

    /**
     * @return Role
     */
    public function countryRole()
    {
        return new Role(Role::COUNTRY);
    }

    /**
     * @return Role
     */
    public function countryApiRole()
    {
        return new Role(Role::COUNTRY_API);
    }

    /**
     * @return Role
     */
    public function countryExportRole()
    {
        return new Role(Role::COUNTRY_IMPORT);
    }

    /**
     * @return Role
     */
    public function siteRole()
    {
        return new Role(Role::SITE);
    }
}
