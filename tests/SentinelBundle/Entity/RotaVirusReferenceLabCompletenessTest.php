<?php


namespace NS\SentinelBundle\Tests\Entity;


use NS\SentinelBundle\Entity\RotaVirus\ReferenceLab;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RotaVirusReferenceLabCompletenessTest extends BaseLabTest
{
    static $minRequiredFields = [
        'lab',
        'specimenCollectionDate',
        'dt_gt',
        'gt_result_g',
        'gt_result_p',
        'pcr_vp6_result',
        'lab_id',
        'dt_sample_recd'
    ];

    public function testEmptyGlobalRotavirusCaseIsIncomplete(): void
    {
        $lab = new ReferenceLab();
        $violations = $this->validator->validate($lab, null, ['Completeness']);

        $types = $this->countViolations($violations);

        self::assertCount(8, $violations);
        self::assertCount(2, $types);
        self::assertArrayHasKey(ArrayChoiceConstraint::class, $types);
        self::assertArrayHasKey(NotBlank::class, $types);
        self::assertEquals(5, $types[NotBlank::class]);
        self::assertEquals(3, $types[ArrayChoiceConstraint::class]);

        $violations = $this->mapViolations($this->validator->validate($lab, null, ['Completeness']));

        self::assertCount(8, $violations);

        foreach (static::$minRequiredFields as $field)
        {
            self::assertContains($field, $violations);
        }
    }

    protected function getBaseValidEntity(): ReferenceLab
    {
        $lab = new ReferenceLab();
        $lab->setLab(new \NS\SentinelBundle\Entity\ReferenceLab());
        $this->populateBaseFields($lab);

        return $lab;
    }

    public function testMinRequiredFields(): void
    {
        $lab = $this->getBaseValidEntity();

        $violations = $this->validator->validate($lab, null, ['Completeness']);

        self::assertCount(0, $violations);
    }
}
