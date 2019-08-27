<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\RotaVirus\ExternalLab;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;

abstract class BaseLabTest extends BaseRVTest
{
    static $genotypingResultGFields, $genotypeResultPFields;

    public function setUp()
    {
        parent::setUp();

        static::$genotypingResultGFields = [ //fields required by gt_result_g
            'gt_result_g_specify' => [
                'parents' => [
                    'gt_result_g' => [new GenotypeResultG(GenotypeResultG::OTHER), new GenotypeResultG(GenotypeResultG::MIXED)]
                ],
                'pass_value' => 'foo'
            ]
        ];

        static::$genotypeResultPFields = [ //fields required by gt_result_g
            'gt_result_p_specify' => [
                'parents' => [
                    'gt_result_P' => [new GenotypeResultP(GenotypeResultP::OTHER), new GenotypeResultP(GenotypeResultP::MIXED)]
                ],
                'pass_value' => 'foo'
            ]
        ];
    }

    public function populateBaseFields(ExternalLab &$lab)
    {
        $lab->setSpecimenCollectionDate(new \DateTime());
        $lab->setDtGt(new \DateTime());
        $lab->setGtResultG(new GenotypeResultG(GenotypeResultG::NON_TYPEABLE));
        $lab->setGtResultP(new GenotypeResultP(GenotypeResultP::NON_TYPEABLE));
        $lab->setPcrVp6Result(new ElisaResult(ElisaResult::UNKNOWN));
        $lab->setLabId(1);
        $lab->setDtSampleRecd(new \DateTime());
    }

    public function testGenotypingResultGFields(): void
    {
        $this->_testOtherConstraints(static::$genotypingResultGFields);
    }

    public function testGenotypeResultPFields(): void
    {
        $this->_testOtherConstraints(static::$genotypeResultPFields);
    }
}
