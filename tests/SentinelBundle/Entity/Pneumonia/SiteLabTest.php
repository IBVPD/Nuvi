<?php

namespace NS\SentinelBundle\Tests\Entity\Pneumonia;

use DateTime;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\Pneumonia\SiteLab;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SiteLabTest extends TestCase
{
    /** @var SiteLab */
    private $lab;

    /** @var Pneumonia */
    private $case;

    /** @var Region */
    private $region;

    /** @var ValidatorInterface */
    private $validator;

    public function setUp()
    {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->region = new Region('RCODE', 'Test Region');
        $this->case   = new Pneumonia();
        $this->case->setRegion($this->region);

        $this->lab = new SiteLab($this->case);
    }

    static private $minRequiredFields = [
        'nl_isol_blood_sent',
        'nl_broth_sent',
        'nl_other_sent',
    ];

    private static $relatedRequired = [
        'blood_id',
        'blood_lab_date',
        'blood_lab_time',
        'blood_cult_done',
        'blood_gram_done',
        'blood_pcr_done',
    ];

    private static $amrRelatedRequired = [
        'blood_second_id',
        'blood_second_lab_date',
        'blood_second_lab_time',
        'blood_second_cult_done',
        'blood_second_gram_done',
        'blood_second_pcr_done',
        'pleural_fluid_culture_done',
        'pleural_fluid_gram_done',
        'pleural_fluid_pcr_done',
    ];

    static private $amrRequiredFields = [
    ];

    private static $amrExcludedRequired = [
        'other_id',
        'other_type',
        'other_lab_date',
        'other_lab_time',
        'other_cult_done',
        'other_test_done',
    ];

    public function testCompleteSiteLab(): void
    {
        $this->lab->setBloodId('id');
        $this->lab->setBloodLabDate(new DateTime());
        $this->lab->setBloodLabTime(new DateTime());
        $this->lab->setBloodCultDone(new TripleChoice(TripleChoice::NO));
        $this->lab->setBloodPcrDone(new TripleChoice(TripleChoice::NO));
        $this->lab->setBloodGramDone(new TripleChoice(TripleChoice::NO));
        $this->lab->setNlBrothSent(false);
        $this->lab->setNlIsolBloodSent(false);
        $this->lab->setNlOtherSent(false);
        $violations = $this->validator->validate($this->lab, null, ['Completeness']);
        self::assertCount(0, $violations);
        $violations = $this->validator->validate($this->lab, null, ['AMR+Completeness', 'Completeness']);
        self::assertCount(0, $violations);
        $this->lab->setPleuralFluidCultureDone(new TripleChoice(TripleChoice::NO));
        $this->lab->setPleuralFluidGramDone(new TripleChoice(TripleChoice::NO));
        $this->lab->setPleuralFluidPcrDone(new TripleChoice(TripleChoice::NO));
        $violations = $this->validator->validate($this->lab, null, ['AMR+Completeness', 'Completeness']);
        self::assertCount(0, $violations);
    }

    public function testEmptySiteLab(): void
    {
        $violations = $this->validator->validate($this->lab, null, ['Completeness']);
        self::assertCount(count(static::$minRequiredFields), $violations);

        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));
        foreach (static::$minRequiredFields as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }

        // AMR Only requirements
        $violations = $this->validator->validate($this->lab, null, ['AMR+Completeness']);
        self::assertCount(count(static::$amrRequiredFields), $violations);

        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));

        foreach (static::$amrRequiredFields as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }
    }

    public function testEmptySiteLabWithRelatedCaseVariables(): void
    {
        $tripleYes = new TripleChoice(TripleChoice::YES);
        $this->case->setPleuralFluidCollected($tripleYes);
        $this->case->setBloodCollected($tripleYes);
        $this->case->setBloodNumberOfSamples(2);
        $this->case->setOtherSpecimenCollected(new OtherSpecimen(OtherSpecimen::JOINT));

        $violations = $this->validator->validate($this->lab, null, ['Completeness']);
        self::assertCount(count(static::$minRequiredFields) + count(static::$relatedRequired), $violations);
        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));
        foreach (static::$minRequiredFields as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }

        foreach (static::$relatedRequired as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }

        // AMR Only requirements (blood second stuff)
        $violations = $this->validator->validate($this->lab, null, ['AMR+Completeness']);
        self::assertCount(count(static::$amrRelatedRequired)+count(static::$amrRequiredFields), $violations);
        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));

        foreach (static::$amrRelatedRequired as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }

        // Non AMR Only requirements
        $violations = $this->validator->validate($this->lab, null, ['ARF+Completeness']);
        self::assertCount(count(static::$amrExcludedRequired), $violations);

        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));

        foreach (static::$amrExcludedRequired as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }
    }

    /**
     * @param array       $values
     * @param string      $expected
     *
     * @param string|null $group
     *
     * @dataProvider getOtherFieldBranch
     */
    public function testOtherFieldBranch(array $values, string $expected, ?string $group = 'Completeness'): void
    {
        foreach($values as $setterMethod => $fieldValue) {
            $this->lab->$setterMethod($fieldValue);
        }

        /** @var ConstraintViolationListInterface $violations */
        $violations = $this->validator->validate($this->lab, null, [$group]);
        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return sprintf('%s:::%s', $violation->getPropertyPath(), $violation->getMessage());
        }, iterator_to_array($violations->getIterator()));

        self::assertContains($expected, $properties);
    }

    public function getOtherFieldBranch(): array
    {
        return [
            [
                ['setBloodCultDone'=> new TripleChoice(TripleChoice::YES)],
                'bloodCultResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodCultDone'=> new TripleChoice(TripleChoice::YES),
                    'setBloodCultResult'=> new CultureResult(CultureResult::OTHER),
                ],
                'bloodCultOther:::This value should not be blank',
            ],
            [
                ['setBloodPcrDone'=> new TripleChoice(TripleChoice::YES)],
                'bloodPcrResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodPcrDone'=> new TripleChoice(TripleChoice::YES),
                    'setBloodPcrResult'=> new PCRResult(PCRResult::OTHER),
                ],
                'bloodPcrOther:::This value should not be blank',
            ],
            [
                ['setBloodGramDone'=> new TripleChoice(TripleChoice::YES)],
                'bloodGramStain:::This value should not be blank',
            ],
            [
                [
                    'setBloodGramDone'=> new TripleChoice(TripleChoice::YES),
                    'setBloodGramStain'=> new GramStain(GramStain::GM_POSITIVE),
                ],
                'bloodGramResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodGramDone'=> new TripleChoice(TripleChoice::YES),
                    'setBloodGramStain'=> new GramStain(GramStain::GM_NEGATIVE),
                ],
                'bloodGramResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodGramDone'=> new TripleChoice(TripleChoice::YES),
                    'setBloodGramStain'=> new GramStain(GramStain::GM_VARIABLE),
                ],
                'bloodGramResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodGramDone'=> new TripleChoice(TripleChoice::YES),
                    'setBloodGramStain'=> new GramStain(GramStain::GM_POSITIVE),
                    'setBloodGramResult'=> new GramStainResult(GramStainResult::OTHER),
                ],
                'bloodGramOther:::This value should not be blank',
            ],
            [
                ['setOtherCultDone'=> new TripleChoice(TripleChoice::YES)],
                'otherCultResult:::This value should not be blank',
            ],
            [
                [
                    'setOtherCultDone'=> new TripleChoice(TripleChoice::YES),
                    'setOtherCultResult'=> new CultureResult(CultureResult::OTHER),
                ],
                'otherCultOther:::This value should not be blank',
            ],
            [
                ['setOtherTestDone'=> new TripleChoice(TripleChoice::YES)],
                'otherTestResult:::This value should not be blank',
            ],
            [
                [
                    'setOtherTestDone'=> new TripleChoice(TripleChoice::YES),
                    'setOtherTestResult'=> new CultureResult(CultureResult::OTHER),
                ],
                'otherTestOther:::This value should not be blank',
            ],

            [
                ['setPleuralFluidCultureDone'=> new TripleChoice(TripleChoice::YES)],
                'pleuralFluidCultureResult:::This value should not be blank',
                'AMR+Completeness',
            ],
[
                [
                    'setPleuralFluidCultureDone'=> new TripleChoice(TripleChoice::YES),
                    'setPleuralFluidCultureResult'=> new CultureResult(CultureResult::OTHER),
                ],
                'pleuralFluidCultureOther:::This value should not be blank',
                'AMR+Completeness',
            ],

            [
                ['setPleuralFluidPcrDone'=> new TripleChoice(TripleChoice::YES)],
                'pleuralFluidPcrResult:::This value should not be blank',
                'AMR+Completeness',
            ],
            [
                [
                    'setPleuralFluidPcrDone'=> new TripleChoice(TripleChoice::YES),
                    'setPleuralFluidPcrResult'=> new PCRResult(PCRResult::OTHER),
                ],
                'pleuralFluidPcrOther:::This value should not be blank',
                'AMR+Completeness',
            ],
            [
                ['setPleuralFluidGramDone'=> new TripleChoice(TripleChoice::YES)],
                'pleuralFluidGramResult:::This value should not be blank',
                'AMR+Completeness',
            ],
            [
                [
                    'setPleuralFluidGramDone'=> new TripleChoice(TripleChoice::YES),
                    'setPleuralFluidGramResult'=> new GramStain(GramStain::GM_POSITIVE),
                ],
                'pleuralFluidGramResultOrganism:::This value should not be blank',
                'AMR+Completeness',
            ],
            [
                [
                    'setPleuralFluidGramDone'=> new TripleChoice(TripleChoice::YES),
                    'setPleuralFluidGramResult'=> new GramStain(GramStain::GM_NEGATIVE),
                ],
                'pleuralFluidGramResultOrganism:::This value should not be blank',
                'AMR+Completeness',
            ],
            [
                [
                    'setPleuralFluidGramDone'=> new TripleChoice(TripleChoice::YES),
                    'setPleuralFluidGramResult'=> new GramStain(GramStain::GM_VARIABLE),
                ],
                'pleuralFluidGramResultOrganism:::This value should not be blank',
                'AMR+Completeness',
            ],

        ];
    }
}
