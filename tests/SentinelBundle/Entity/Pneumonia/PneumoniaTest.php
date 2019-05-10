<?php

namespace NS\SentinelBundle\Tests\Entity\Pneumonia;

use DateTime;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PneumoniaTest extends TestCase
{
    /** @var Pneumonia */
    private $case;

    /** @var Region */
    private $region;

    /** @var ValidatorInterface */
    private $validator;

    public function setUp()
    {
        $this->region = new Region('RCODE', 'Test Region');
        $this->case   = new Pneumonia();
        $this->case->setRegion($this->region);
        $this->case->setAdmDx();

        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    static private $minRequiredFields = [
        'case_id',
        'district',
        'dobKnown',
        'birthdate',
        'age_months',
        'gender',
        'adm_date',
        'onset_date',
        //this only fails if we specifically unset the adm_dx
        'adm_dx',
        'antibiotics',
        'pneu_diff_breathe',
        'pneu_chest_indraw',
        'pneu_cough',
        'pneu_cyanosis',
        'pneu_stridor',
        'pneu_resp_rate',
        'pneu_vomit',
        'pneu_hypothermia',
        'pneu_malnutrition',
        'pneu_fever',
        'cxr_done',
        'hib_received',
        'pcv_received',
        'mening_received',
        'blood_collected',
        'disch_outcome',
        'disch_dx',
        'disch_class',
    ];

    public function testCompleteCase(): void
    {
        $this->case->setCaseId('12');
        $this->case->setDistrict('district');
        $this->case->setDobKnown(new TripleChoice(TripleChoice::YES));
        $this->case->setAge(0);
        $this->case->setDob(new DateTime());
        $this->case->setGender(new Gender(Gender::UNKNOWN));
        $this->case->setAdmDx(new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA));
        $this->case->setOnsetDate(new DateTime());
        $this->case->setAdmDate(new DateTime());
        $tripleNo = new TripleChoice(TripleChoice::NO);
        $this->case->setBloodCollected($tripleNo);
        $this->case->setPneuDiffBreathe($tripleNo);
        $this->case->setPneuChestIndraw($tripleNo);
        $this->case->setPneuCough($tripleNo);
        $this->case->setPneuCyanosis($tripleNo);
        $this->case->setPneuStridor($tripleNo);
        $this->case->setPneuRespRate(50);
        $this->case->setPneuVomit($tripleNo);
        $this->case->setPneuHypothermia($tripleNo);
        $this->case->setPneuMalnutrition($tripleNo);
        $this->case->setPneuFever($tripleNo);
        $this->case->setCxrDone($tripleNo);
        $vaccinationReceived = new VaccinationReceived(VaccinationReceived::NO);
        $this->case->setHibReceived($vaccinationReceived);
        $this->case->setPcvReceived($vaccinationReceived);
        $this->case->setMeningReceived($vaccinationReceived);

        $this->case->setAntibiotics($tripleNo);
        $this->case->setDischOutcome(new DischargeOutcome(DischargeOutcome::UNKNOWN));
        $this->case->setDischClass(new DischargeClassification(DischargeClassification::UNKNOWN));
        $this->case->setDischDx(new DischargeDiagnosis(DischargeDiagnosis::UNKNOWN));
        $this->case->setOtherSpecimenCollected(new OtherSpecimen(OtherSpecimen::NONE));
        $violations = $this->validator->validate($this->case, null, ['Completeness']);
        self::assertCount(0, $violations);
    }

    public function testIncompletePneumonia(): void
    {
        $violations = $this->validator->validate($this->case, null, ['Completeness']);
        self::assertCount(28, $violations);

        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));

        foreach (static::$minRequiredFields as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }

        // AMR Only requirements
        $violations = $this->validator->validate($this->case, null, ['AMR+Completeness']);
        self::assertCount(2, $violations);

        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));
        self::assertContains('pneu_oxygen_saturation', $properties);
        self::assertContains('pleural_fluid_collected', $properties);

        // Combined
        $violations = $this->validator->validate($this->case, null, ['AMR+Completeness', 'Completeness']);
        self::assertCount(30, $violations);

        // AMR Excluded (required by WHO but AMR hides it)
        $violations = $this->validator->validate($this->case, null, ['ARF+Completeness']);
        self::assertCount(1, $violations);
    }

    public function testBloodNumberOfSamples(): void
    {
        $this->case->setPneuOxygenSaturation(51);
        $this->case->setPleuralFluidCollected(new TripleChoice(TripleChoice::NO));
        $this->case->setBloodCollected(new TripleChoice(TripleChoice::YES));

        $violations = $this->validator->validate($this->case, null, ['AMR+Completeness']);
        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));

        self::assertCount(1, $violations);
        self::assertContains('bloodNumberOfSamples', $properties);

        $this->case->setBloodNumberOfSamples(2);
        $violations = $this->validator->validate($this->case, null, ['AMR+Completeness']);
        self::assertCount(2, $violations);
        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));
        self::assertContains('blood_second_collect_date', $properties);
        self::assertContains('blood_second_collect_time', $properties);
    }

    /**
     * @param string      $setterMethod
     * @param             $fieldValue
     * @param string      $expected
     *
     * @param string|null $group
     *
     * @dataProvider getOtherFieldBranch
     */
    public function testOtherFieldBranch(string $setterMethod, $fieldValue, string $expected, ?string $group = 'Completeness'): void
    {
        $this->case->$setterMethod($fieldValue);
        /** @var ConstraintViolationListInterface $violations */
        $violations = $this->validator->validate($this->case, null, [$group]);
        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return sprintf('%s:::%s', $violation->getPropertyPath(), $violation->getMessage());
        }, iterator_to_array($violations->getIterator()));

        self::assertContains($expected, $properties);
    }

    public function getOtherFieldBranch(): array
    {
        return [
//            [
//                'setBloodNumberOfSamples',
//                2,
//                'blood_second_collect_date:::This value should not be blank',
//                'AMR',
//            ],
//            [
//                'setBloodNumberOfSamples',
//                2,
//                'blood_second_collect_time:::This value should not be blank',
//                'AMR',
//            ],
            [
                'setAdmDx',
                new Diagnosis(Diagnosis::OTHER),
                'admDxOther:::This value should not be blank',
            ],
            [
                'setAntibiotics',
                new TripleChoice(TripleChoice::YES),
                'antibioticName:::This value should not be blank',
            ],
            [
                'setDischDx',
                new DischargeDiagnosis(DischargeDiagnosis::OTHER),
                'dischDxOther:::This value should not be blank',
            ],

            [
                'setDischClass',
                new DischargeClassification(DischargeClassification::CONFIRMED_OTHER),
                'dischClassOther:::This value should not be blank',
            ],
            [
                'setOtherSpecimenCollected',
                new OtherSpecimen(OtherSpecimen::OTHER),
                'otherSpecimenOther:::This value should not be blank',
                'ARF+Completeness',
            ],
            [
                'setPleuralFluidCollected',
                new TripleChoice(TripleChoice::YES),
                'pleuralFluidCollectDate:::This value should not be blank',
            ],
            [
                'setPleuralFluidCollected',
                new TripleChoice(TripleChoice::YES),
                'pleuralFluidCollectTime:::This value should not be blank',
            ],
            [
                'setHibReceived',
                new VaccinationReceived(VaccinationReceived::YES_CARD),
                'hibDoses:::This value should not be blank',
            ],
            [
                'setHibReceived',
                new VaccinationReceived(VaccinationReceived::YES_HISTORY),
                'hibMostRecentDose:::This value should not be blank',
            ],

            [
                'setPcvReceived',
                new VaccinationReceived(VaccinationReceived::YES_CARD),
                'pcvType:::This value should not be blank',
            ],
            [
                'setPcvReceived',
                new VaccinationReceived(VaccinationReceived::YES_HISTORY),
                'pcvMostRecentDose:::This value should not be blank',
            ],

            [
                'setMeningReceived',
                new VaccinationReceived(VaccinationReceived::YES_CARD),
                'meningType:::This value should not be blank',
            ],
            [
                'setMeningReceived',
                new VaccinationReceived(VaccinationReceived::YES_HISTORY),
                'meningDate:::This value should not be blank',
            ],
        ];
    }
}
