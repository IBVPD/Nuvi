<?php

namespace NS\SentinelBundle\Tests\Entity\Meningitis;

use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Meningitis\SiteLab;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\LatResult;
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

    /** @var Meningitis */
    private $case;

    /** @var Region */
    private $region;

    /** @var ValidatorInterface */
    private $validator;

    public function setUp()
    {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->region    = new Region('RCODE', 'Test Region');
        $this->case      = new Meningitis();
        $this->case->setRegion($this->region);

        $this->lab = new SiteLab($this->case);
    }

    static private $minRequiredFields = [
        'nl_csf_sent',
        'nl_isol_csf_sent',
        'nl_isol_blood_sent',
        'nl_broth_sent',
        'nl_other_sent',
    ];

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
        self::assertCount(0, $violations);
    }

    public function testCompleteSiteLab(): void
    {
        $tripleNo = new TripleChoice(TripleChoice::NO);
        $this->case->setBloodCollected($tripleNo);
        $this->case->setCsfCollected($tripleNo);
        $this->case->setOtherSpecimenCollected(new OtherSpecimen(OtherSpecimen::NONE));
        $this->lab->setNlCsfSent(false);
        $this->lab->setNlIsolCsfSent(false);
        $this->lab->setNlIsolBloodSent(false);
        $this->lab->setNlBrothSent(false);
        $this->lab->setNlOtherSent(false);
        $violations = $this->validator->validate($this->lab, null, ['Completeness']);
        self::assertCount(0, $violations);
        $violations = $this->validator->validate($this->lab, null, ['AMR+Completeness', 'Completeness']);
        self::assertCount(0, $violations);
    }

    private static $relatedRequired = [
        'csf_id',
        'csf_lab_date',
        'csf_lab_time',
        'csf_wcc',
        'csf_glucose',
        'csf_protein',
        'csf_cult_done',
        'csf_gram_done',
        'csf_binax_done',
        'csf_lat_done',
        'csf_pcr_done',
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
    ];

    private static $amrExcludedRequired = [
        'other_id',
        'other_type',
        'other_lab_date',
        'other_lab_time',
        'other_cult_done',
        'other_test_done',
    ];

    public function testEmptySiteLabWithRelatedCaseVariables(): void
    {
        $tripleYes = new TripleChoice(TripleChoice::YES);
        $this->case->setBloodCollected($tripleYes);
        $this->case->setBloodNumberOfSamples(2);
        $this->case->setCsfCollected($tripleYes);
        $this->case->setOtherSpecimenCollected(new OtherSpecimen(OtherSpecimen::JOINT));

        $violations = $this->validator->validate($this->lab, null, ['Completeness']);
        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));

        self::assertCount(count(static::$minRequiredFields) + count(static::$relatedRequired), $violations);
        foreach (static::$minRequiredFields as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }

        foreach (static::$relatedRequired as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }

        // AMR Only requirements (blood second stuff)
        $violations = $this->validator->validate($this->lab, null, ['AMR+Completeness']);
        self::assertCount(count(static::$amrRelatedRequired), $violations);

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
        foreach ($values as $setterMethod => $fieldValue) {
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
                ['setBloodCultDone' => new TripleChoice(TripleChoice::YES)],
                'bloodCultResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodCultDone' => new TripleChoice(TripleChoice::YES),
                    'setBloodCultResult' => new CultureResult(CultureResult::OTHER),
                ],
                'bloodCultOther:::This value should not be blank',
            ],
            [
                ['setBloodPcrDone' => new TripleChoice(TripleChoice::YES)],
                'bloodPcrResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodPcrDone' => new TripleChoice(TripleChoice::YES),
                    'setBloodPcrResult' => new PCRResult(PCRResult::OTHER),
                ],
                'bloodPcrOther:::This value should not be blank',
            ],
            [
                ['setBloodGramDone' => new TripleChoice(TripleChoice::YES)],
                'bloodGramStain:::This value should not be blank',
            ],
            [
                [
                    'setBloodGramDone' => new TripleChoice(TripleChoice::YES),
                    'setBloodGramStain' => new GramStain(GramStain::GM_POSITIVE),
                ],
                'bloodGramResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodGramDone' => new TripleChoice(TripleChoice::YES),
                    'setBloodGramStain' => new GramStain(GramStain::GM_NEGATIVE),
                ],
                'bloodGramResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodGramDone' => new TripleChoice(TripleChoice::YES),
                    'setBloodGramStain' => new GramStain(GramStain::GM_VARIABLE),
                ],
                'bloodGramResult:::This value should not be blank',
            ],
            [
                [
                    'setBloodGramDone' => new TripleChoice(TripleChoice::YES),
                    'setBloodGramStain' => new GramStain(GramStain::GM_POSITIVE),
                    'setBloodGramResult' => new GramStainResult(GramStainResult::OTHER),
                ],
                'bloodGramOther:::This value should not be blank',
            ],
            [
                ['setOtherCultDone' => new TripleChoice(TripleChoice::YES)],
                'otherCultResult:::This value should not be blank',
            ],
            [
                [
                    'setOtherCultDone' => new TripleChoice(TripleChoice::YES),
                    'setOtherCultResult' => new CultureResult(CultureResult::OTHER),
                ],
                'otherCultOther:::This value should not be blank',
            ],
            [
                ['setOtherTestDone' => new TripleChoice(TripleChoice::YES)],
                'otherTestResult:::This value should not be blank',
            ],
            [
                [
                    'setOtherTestDone' => new TripleChoice(TripleChoice::YES),
                    'setOtherTestResult' => new CultureResult(CultureResult::OTHER),
                ],
                'otherTestOther:::This value should not be blank',
            ],


            [
                ['setCsfLatDone' => new TripleChoice(TripleChoice::YES)],
                'csfLatResult:::This value should not be blank',
            ],
            [
                [
                    'setCsfLatDone' => new TripleChoice(TripleChoice::YES),
                    'setCsfLatResult' => new LatResult(LatResult::OTHER),
                ],
                'csfLatOther:::This value should not be blank',
            ],
            [
                ['setCsfCultDone' => new TripleChoice(TripleChoice::YES)],
                'csfCultResult:::This value should not be blank',
            ],
            [
                [
                    'setCsfCultDone' => new TripleChoice(TripleChoice::YES),
                    'setCsfCultResult' => new CultureResult(CultureResult::OTHER),
                ],
                'csfCultOther:::This value should not be blank',
            ],
            [
                ['setCsfBinaxDone' => new TripleChoice(TripleChoice::YES)],
                'csfBinaxResult:::This value should not be blank',
            ],
            [
                ['setCsfPcrDone' => new TripleChoice(TripleChoice::YES)],
                'csfPcrResult:::This value should not be blank',
            ],
            [
                [
                    'setCsfPcrDone' => new TripleChoice(TripleChoice::YES),
                    'setCsfPcrResult' => new PCRResult(PCRResult::OTHER),
                ],
                'csfPcrOther:::This value should not be blank',
            ],
            [
                ['setCsfGramDone' => new TripleChoice(TripleChoice::YES)],
                'csfGramStain:::This value should not be blank',
            ],
            [
                [
                    'setCsfGramDone' => new TripleChoice(TripleChoice::YES),
                    'setCsfGramStain' => new GramStain(GramStain::GM_POSITIVE),
                ],
                'csfGramResult:::This value should not be blank',
            ],
            [
                [
                    'setCsfGramDone' => new TripleChoice(TripleChoice::YES),
                    'setCsfGramStain' => new GramStain(GramStain::GM_NEGATIVE),
                ],
                'csfGramResult:::This value should not be blank',
            ],
            [
                [
                    'setCsfGramDone' => new TripleChoice(TripleChoice::YES),
                    'setCsfGramStain' => new GramStain(GramStain::GM_VARIABLE),
                ],
                'csfGramResult:::This value should not be blank',
            ],
            [
                [
                    'setCsfGramDone' => new TripleChoice(TripleChoice::YES),
                    'setCsfGramStain' => new GramStain(GramStain::GM_POSITIVE),
                    'setCsfGramResult' => new GramStainResult(GramStainResult::OTHER),
                ],
                'csfGramOther:::This value should not be blank',
            ],
        ];
    }
}
