<?php


namespace NS\SentinelBundle\Tests\Entity;


use NS\SentinelBundle\Entity\RotaVirus\ReferenceLab;
use NS\SentinelBundle\Entity\RotaVirus\SiteLab;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class RotaVirusSiteLabCompletenessTest extends BaseRVTest
{
    static $minRequiredFields = [
        'received',
        'adequate',
        'stored',
        'elisaDone',
//        'genotypingDate',
//        'genotypingResultG',
//        'genotypeResultP',
        'stoolSentToNL',
    ];

    static $elisaDoneFields, $elisaKitFields, $stoolSentToNLFields, $stoolSentToRRLFields;

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

        static::$stoolSentToNLFields = [ //fields required by stoolSentToNL
            'stoolSentToNLDate' => [
                'parents'    => [
                    'stoolSentToNL' => [$this->tripleChoiceYes]
                ],
                'pass_value' => new \DateTime()
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
        // TODO: Implement getBaseValidEntity() method.
        $lab = new SiteLab();
        $lab->setReceived(new \DateTime());
        $lab->setAdequate($this->tripleChoiceNo);
        $lab->setStored($this->tripleChoiceNo);
        $lab->setElisaDone($this->tripleChoiceNo);
        $lab->setGenotypingDate(new \DateTime());
        $lab->setGenotypingResultg(new GenotypeResultG(GenotypeResultG::NON_TYPEABLE));
        $lab->setGenotypeResultP(new GenotypeResultP(GenotypeResultP::NON_TYPEABLE));
        $lab->setStoolSentToNL($this->tripleChoiceNo);

        return $lab;
    }

    public function testEmptyGlobalRotavirusReferenceLabIsIncomplete(): void
    {
        $lab = new SiteLab();
        $violations = $this->validator->validate($lab, null, ['Completeness']);

        $types = $this->countViolations($violations);

        self::assertCount(10, $violations);
        self::assertCount(2, $types);
        self::assertArrayHasKey(ArrayChoiceConstraint::class, $types);
        self::assertArrayHasKey(NotBlank::class, $types);
        self::assertEquals(7, $types[NotBlank::class]);
        self::assertEquals(3, $types[ArrayChoiceConstraint::class]);

        $violations = $this->mapViolations($this->validator->validate($lab, null, ['Completeness']));

        self::assertCount(10, $violations);

        foreach (static::$minRequiredFields as $field) {
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

    public function testStoolSentToNLFields(): void
    {
        $this->_testOtherConstraints(static::$stoolSentToNLFields);
    }

    public function testStoolSentToRRLFields(): void
    {
        $this->_testOtherConstraints(static::$stoolSentToRRLFields);
    }

}
