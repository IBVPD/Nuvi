<?php


namespace NS\SentinelBundle\Tests\Entity;


use NS\SentinelBundle\Entity\RotaVirus\NationalLab;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class RotaVirusNationalLabCompletenessTest extends BaseLabTest
{
    static $minRequiredFields = [
        'elisaDone',
        'stoolSentToRRL',
        'specimenCollectionDate',
        'dt_gt',
        'gt_result_g',
        'gt_result_p',
        'pcr_vp6_result',
        'lab_id',
        'dt_sample_recd'
    ];

    static $elisaDoneFields, $elisaKitFields, $stoolSentToRRLFields;

    public function setUp()
    {
        parent::setUp();

        static::$elisaDoneFields = [ //fields required by elisaDone
            'elisaKit'        => [
                'parents'    => [
                    'elisaDone' => [$this->tripleChoiceYes]
                ],
                'pass_value' => new ElisaKit(ElisaKit::PROSPECT)
            ],
            'elisaLoadNumber' => [
                'parents'    => [
                    'elisaDone' => [$this->tripleChoiceYes]
                ],
                'pass_value' => 'foo'
            ],
            'elisaExpiryDate' => [
                'parents'    => [
                    'elisaDone' => [$this->tripleChoiceYes]
                ],
                'pass_value' => new \DateTime()
            ],
            'elisaTestDate'   => [
                'parents'    => [
                    'elisaDone' => [$this->tripleChoiceYes]
                ],
                'pass_value' => new \DateTime()
            ],
            'elisaResult'     => [
                'parents'    => [
                    'elisaDone' => [$this->tripleChoiceYes]
                ],
                'pass_value' => new ElisaResult(ElisaResult::UNKNOWN)
            ],
        ];

        static::$elisaKitFields = [ //fields required by elisaKit
            'elisaKitOther' => [
                'parents'    => [
                    'elisaKit' => [new ElisaKit(ElisaKit::OTHER)]
                ],
                'pass_value' => 'foo'
            ],
        ];

        static::$stoolSentToRRLFields = [ //fields required by stoolSentToRRL
            'stoolSentToRRLDate' => [
                'parents'    => [
                    'stoolSentToRRL' => [$this->tripleChoiceYes]
                ],
                'pass_value' => new \DateTime()
            ],
        ];
    }

    protected function getBaseValidEntity()
    {
        $lab = new NationalLab();
        $lab->setElisaDone($this->tripleChoiceNo);
        $lab->setStoolSentToRRL($this->tripleChoiceNo);
        $this->populateBaseFields($lab);

        return $lab;
    }

    public function testEmptyGlobalRotavirusNationalLabIsIncomplete(): void
    {
        $lab = new NationalLab();
        $violations = $this->validator->validate($lab, null, ['Completeness']);

        $types = $this->countViolations($violations);

        self::assertCount(9, $violations);
        self::assertCount(2, $types);
        self::assertArrayHasKey(ArrayChoiceConstraint::class, $types);
        self::assertArrayHasKey(NotBlank::class, $types);
        self::assertEquals(4, $types[NotBlank::class]);
        self::assertEquals(5, $types[ArrayChoiceConstraint::class]);

        $violations = $this->mapViolations($this->validator->validate($lab, null, ['Completeness']));

        self::assertCount(9, $violations);

        foreach (static::$minRequiredFields as $field)
        {
            self::assertContains($field, $violations);
        }
    }

    public function testMinRequiredFields(): void
    {
        $lab = $this->getBaseValidEntity();

        $violations = $this->validator->validate($lab, null, ['Completeness']);

        self::assertCount(0, $violations);
    }

    public function testElisaDoneFields(): void
    {
        $this->_testOtherConstraints(static::$elisaDoneFields);
    }

    public function testElisaKitFields(): void
    {
        $this->_testOtherConstraints(static::$elisaKitFields);
    }

    public function testStoolSentToRRLFields(): void
    {
        $this->_testOtherConstraints(static::$stoolSentToRRLFields);
    }
}
