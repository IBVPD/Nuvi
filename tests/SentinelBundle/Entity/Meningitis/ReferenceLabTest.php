<?php

namespace NS\SentinelBundle\Tests\Entity\Meningitis;

use DateTime;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Meningitis\SiteLab;
use NS\SentinelBundle\Entity\Meningitis\ReferenceLab;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Form\IBD\Types\FinalResult;
use NS\SentinelBundle\Form\IBD\Types\IsolateType;
use NS\SentinelBundle\Form\IBD\Types\IsolateViable;
use NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SampleType;
use NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReferenceLabTest extends TestCase
{
    /** @var ReferenceLab */
    private $lab;

    /** @var SiteLab */
    private $siteLab;

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

        $this->siteLab = new SiteLab($this->case);

        $this->lab = new ReferenceLab();
        $this->lab->setCaseFile($this->case);
    }

    static private $minRequiredFields = [
        'lab_id',
        'dt_sample_recd',
        'type_sample_recd',
        'method_used_pathogen_identify',
        'method_used_st_sg',
        'spn_lytA',
        'nm_ctrA',
        'nm_sodC',
        'hi_hpd1',
        'hi_hpd3',
        'hi_bexA',
        'humanDNA_RNAseP',
        'final_RL_result_detection',
    ];

    public function testCompleteNationalLab(): void
    {
        $this->lab->setLabId('1');
        $this->lab->setDtSampleRecd(new DateTime());
        $this->lab->setTypeSampleRecd(new SampleType(SampleType::CSF));
        $this->lab->setIsolateViable(new IsolateViable(IsolateViable::YES));
        $this->lab->setIsolateType(new IsolateType(IsolateType::HI));
        $this->lab->setMethodUsedPathogenIdentify(new PathogenIdentifier(PathogenIdentifier::CONVENTIONAL));
        $this->lab->setMethodUsedStSg(new SerotypeIdentifier(SerotypeIdentifier::CONVENTIONAL));
        $this->lab->setSpnLytA(1);
        $this->lab->setNmCtrA(1);
        $this->lab->setNmSodC(1);
        $this->lab->setHiHpd1(1);
        $this->lab->setHiHpd3(1);
        $this->lab->setHiBexA(1);
        $this->lab->setHumanDNARNAseP(1);
        $this->lab->setFinalRLResultDetection(new FinalResult(FinalResult::NEG));

        $violations = $this->validator->validate($this->lab, null, ['Completeness']);
        self::assertCount(0, $violations);

        $violations = $this->validator->validate($this->lab, null, ['AMR+Completeness', 'Completeness']);
        self::assertCount(0, $violations);
    }

    public function testEmptyNationalLab(): void
    {
        $violations = $this->validator->validate($this->lab, null, ['Completeness']);
        self::assertCount(count(static::$minRequiredFields), $violations);

        $properties = array_map(static function (ConstraintViolationInterface $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));
        foreach (static::$minRequiredFields as $propertyPath) {
            self::assertContains($propertyPath, $properties);
        }

        // No AMR only requirements
        $violations = $this->validator->validate($this->lab, null, ['AMR+Completeness']);
        self::assertCount(0, $violations);
    }

    /**
     * @param string      $setterMethod
     * @param array       $values
     * @param string      $expected
     *
     * @param string|null $group
     *
     * @dataProvider getOtherFieldBranch
     */
    public function testOtherFieldBranch(string $setterMethod, array $values, string $expected, ?string $group = 'Completeness'): void
    {
        foreach($values['values'] as $value) {
            $values['obj']->setValue($value);
            $this->lab->$setterMethod($values['obj']);
            /** @var ConstraintViolationListInterface $violations */
            $violations = $this->validator->validate($this->lab, null, [$group]);
            $properties = array_map(static function (ConstraintViolationInterface $violation) {
                return sprintf('%s:::%s', $violation->getPropertyPath(), $violation->getMessage());
            }, iterator_to_array($violations->getIterator()));

            self::assertContains($expected, $properties);
        }
    }

    public function getOtherFieldBranch(): array
    {
        return [
            [
                'setTypeSampleRecd',
                ['obj' => new SampleType(), 'values' => [SampleType::ISOLATE]],
                'isolateViable:::This value should not be blank',
            ],
            [
                'setTypeSampleRecd',
                ['obj' => new SampleType(), 'values' => [SampleType::ISOLATE]],
                'isolateType:::This value should not be blank',
            ],
            [
                'setMethodUsedPathogenIdentify',
                ['obj' => new PathogenIdentifier(), 'values' => [PathogenIdentifier::OTHER]],
                'methodUsedPathogenIdentifyOther:::This value should not be blank',
            ],
            [
                'setMethodUsedStSg',
                ['obj' => new SerotypeIdentifier(), 'values' => [SerotypeIdentifier::OTHER]],
                'methodUsedStSgOther:::This value should not be blank',
            ],
            [
                'setFinalResult',
                ['obj' => new FinalResult(), 'values' => [FinalResult::SPN, FinalResult::SPN_HI, FinalResult::SPN_HI_NM, FinalResult::SPN_NM]],
                'spnSerotype:::This value should not be blank',
            ],
            [
                'setFinalResult',
                ['obj' => new FinalResult(), 'values' => [FinalResult::HI, FinalResult::SPN_HI, FinalResult::SPN_HI_NM, FinalResult::HI_NM]],
                'hiSerotype:::This value should not be blank',
            ],
            [
                'setFinalResult',
                ['obj' => new FinalResult(), 'values' => [FinalResult::NM, FinalResult::SPN_NM, FinalResult::SPN_HI_NM, FinalResult::HI_NM]],
                'nmSerogroup:::This value should not be blank',
            ],
        ];
    }
}
