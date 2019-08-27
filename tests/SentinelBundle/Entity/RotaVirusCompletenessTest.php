<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Form\RotaVirus\Types\Dehydration;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome;
use NS\SentinelBundle\Form\RotaVirus\Types\Rehydration;
use NS\SentinelBundle\Form\RotaVirus\Types\VaccinationType;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RotaVirusCompletenessTest extends BaseRVTest
{
    static private $minRequiredFields = [
        'intensiveCare',
        'symp_diarrhea',
        'symp_vomit',
        'symp_dehydration',
        'rv_received',
        'stool_collected',
        'dobKnown',
        'gender',
        'disch_outcome',
        'disch_class',
        'disch_date',
        'case_id',
        'district',
        'birthdate',
        'age_months',
        'adm_date',
    ];

    static private $sympDiaFields = [ //required by symp_diarrhea
        'symp_dia_onset_date',
        'symp_dia_episodes',
        'symp_dia_duration',
//        'symp_dia_bloody',
    ];

    static private $sympVomFields = [ //required by symp_vomit
        'symp_vomit_episodes',
        'symp_vomit_duration',
    ];

    static private $sympDehydrationFields = [ //required by sym_dehydration
        'rehydration',
    ];

    static private $rehydrationFields = [ //required by rehydration
        'rehydration_type',
    ];

    static private $rehydrationTypeFields = [ //required by rehydration_type
        'rehydration_other',
    ];

    static private $vaccinationReceivedFields = [ //required by rv_received
        'rv_type',
        'rv_doses',
    ];

    static private $vaccinationDoseDateFields = [ //required by rv_doses
        'rv_dose1_date',
        'rv_dose2_date',
        'rv_dose3_date',
    ];

    static private $stoolCollectionFields = [ //required by stool_collected
        'stool_id',
        'stool_collect_date',
    ];

    protected function getBaseValidEntity(): RotaVirus
    {
        $case = new RotaVirus();

        $case->setIntensiveCare($this->tripleChoiceNo);
        $case->setSympDiarrhea($this->tripleChoiceNo);
        $case->setSympDehydration(new Dehydration(Dehydration::UNKNOWN));
        $case->setSympVomit($this->tripleChoiceNo);
        $case->setRvReceived(new VaccinationReceived(VaccinationReceived::NO));
        $case->setStoolCollected($this->tripleChoiceNo);
        $case->setDobKnown($this->tripleChoiceYes);
        $case->setGender(new Gender(Gender::UNKNOWN));
        $case->setDischargeOutcome(new DischargeOutcome(DischargeOutcome::UNKNOWN));
        $case->setCaseId('foo');
        $case->setDistrict('bar');
        $case->setBirthdate(new \DateTime());
        $case->setAgeMonths(0);
        $case->setAdmDate(new \DateTime());
        $case->setDischargeClassification(new DischargeClassification(DischargeClassification::UNKNOWN));
        $case->setDischargeDate(new \DateTime());

        return $case;
    }

    public function testEmptyGlobalRotavirusCaseIsIncomplete(): void
    {
        $case       = new RotaVirus();
        $violations = $this->validator->validate($case, null, ['Completeness']);

        $types = $this->countViolations($violations);

        self::assertCount(21, $violations);
        self::assertCount(2, $types);
        self::assertArrayHasKey(ArrayChoiceConstraint::class, $types);
        self::assertArrayHasKey(NotBlank::class, $types);
        self::assertEquals(8, $types[ArrayChoiceConstraint::class]);
        self::assertEquals(13, $types[NotBlank::class]);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(21, $violations);

        foreach (static::$minRequiredFields as $field) {
            self::assertContains($field, $violations);
        }
    }

    public function testRotavirusMinRequiredFields(): void
    {
        $case = $this->getBaseValidEntity();

        /** @var ConstraintViolationListInterface $violations */
        $violations = $this->validator->validate($case, null, ['Completeness']);

        self::assertCount(0, $violations);
    }

    public function testRotavirusDiarrheaSymptomConstraints(): void
    {
        $case = new RotaVirus();
        $case->setSympDiarrhea($this->tripleChoiceNo);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(19, $violations);

        foreach (static::$sympDiaFields as $field) {
            self::assertNotContains($field, $violations);
        }

        $case->setSympDiarrhea($this->tripleChoiceYes);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        foreach (static::$sympDiaFields as $field) {
            self::assertContains($field, $violations);
        }

        self::assertCount(22, $violations);

        $case->setSympDiaOnsetDate(new \DateTime());
        $case->setSympDiaEpisodes(1);
        $case->setSympDiaDuration(1);
        $case->setSympDiaBloody($this->tripleChoiceYes);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(19, $violations);

        foreach (static::$sympDiaFields as $field) {
            self::assertNotContains($field, $violations);
        }
    }

    public function testRotavirusVomitSymptomConstraints(): void
    {
        $case = new RotaVirus();
        $case->setSympVomit($this->tripleChoiceNo);

        $violations = $this->validator->validate($case, null, ['Completeness']);

        self::assertCount(19, $violations);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        foreach (static::$sympVomFields as $field) {
            self::assertNotContains($field, $violations);
        }

        $case->setSympVomit($this->tripleChoiceYes);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(21, $violations);

        foreach (static::$sympVomFields as $field) {
            self::assertContains($field, $violations);
        }

        $case->setSympVomitDuration(1);
        $case->setSympVomitEpisodes(1);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(19, $violations);

        foreach (static::$sympVomFields as $field) {
            self::assertNotContains($field, $violations);
        }
    }

    public function testRotavirusHydrationConstraints(): void
    {
        $case = new RotaVirus();

        foreach ([Dehydration::NONE, Dehydration::UNKNOWN] as $dehydration) {
            $case->setSympDehydration(new Dehydration($dehydration));

            $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

            self::assertCount(20, $violations);

            foreach (static::$sympDehydrationFields as $field) {
                self::assertNotContains($field, $violations);
            }
        }

        foreach ([Dehydration::SOME, Dehydration::MODERATE, Dehydration::SEVERE] as $dehydration) {
            $case->setSympDehydration(new Dehydration($dehydration));

            $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

            self::assertCount(21, $violations);

            foreach (static::$sympDehydrationFields as $field) {
                self::assertContains($field, $violations);
            }
        }

        $case->setRehydration($this->tripleChoiceNo);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(20, $violations);

        foreach (static::$rehydrationFields as $field) {
            self::assertNotContains($field, $violations);
        }

        $case->setRehydration($this->tripleChoiceYes);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(21, $violations);

        foreach (static::$rehydrationFields as $field) {
            self::assertContains($field, $violations);
        }

        foreach ([Rehydration::BOTH, Rehydration::IV, Rehydration::MULTIPLE, Rehydration::ORAL] as $rehydrationType) {
            $case->setRehydrationType(new Rehydration($rehydrationType));

            $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

            self::assertCount(20, $violations);

            foreach (static::$rehydrationTypeFields as $field) {
                self::assertNotContains($field, $violations);
            }
        }

        $case->setRehydrationType(new Rehydration(Rehydration::OTHER));

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(21, $violations);

        foreach (static::$rehydrationTypeFields as $field) {
            self::assertContains($field, $violations);
        }

        $case->setRehydrationOther('foo');

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(20, $violations);
    }

    public function testRotavirusVaccinationConstraints(): void
    {
        $case = new RotaVirus();

        foreach ([VaccinationReceived::NO, VaccinationReceived::UNKNOWN] as $vaccinationReceived) {
            $case->setVaccinationReceived(new VaccinationReceived($vaccinationReceived));

            $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

            self::assertCount(19, $violations);

            foreach (static::$vaccinationReceivedFields as $field) {
                self::assertNotContains($field, $violations);
            }
        }

        foreach ([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY] as $vaccinationReceived) {
            $case->setVaccinationReceived(new VaccinationReceived($vaccinationReceived));

            $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

            self::assertCount(21, $violations);

            foreach (static::$vaccinationReceivedFields as $field) {
                self::assertContains($field, $violations);
            }
        }

        $case->setRvDoses(new ThreeDoses(ThreeDoses::UNKNOWN));
        $case->setRvType(new VaccinationType(VaccinationType::UNKNOWN));

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(19, $violations);

        foreach ([ThreeDoses::ONE, ThreeDoses::TWO, ThreeDoses::THREE] as $doses) {
            $case->setRvDoses(new ThreeDoses($doses));

            $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

            self::assertCount(19 + $doses, $violations);

            $i = $doses;
            while ($i > 0) {
                self::assertContains(static::$vaccinationDoseDateFields[$i - 1], $violations);
                $i--;
            }
        }

        $case->setRvDose1Date(new \DateTime());
        $case->setRvDose2Date(new \DateTime());
        $case->setRvDose3Date(new \DateTime());

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(19, $violations);
    }

    public function testRotavirusStoolCollectionConstraints(): void
    {
        $case = new RotaVirus();

        $case->setStoolCollected($this->tripleChoiceNo);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(19, $violations);

        foreach (static::$stoolCollectionFields as $field) {
            self::assertNotContains($field, $violations);
        }

        $case->setStoolCollected($this->tripleChoiceYes);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(21, $violations);

        foreach (static::$stoolCollectionFields as $field) {
            self::assertContains($field, $violations);
        }

        $case->setStoolCollectDate(new \DateTime());
        $case->setStoolId(1);

        $violations = $this->mapViolations($this->validator->validate($case, null, ['Completeness']));

        self::assertCount(19, $violations);
    }
}
