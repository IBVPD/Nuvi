<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\UtilBundle\Form\Types\ArrayChoice;
use NS\SentinelBundle\Converter\ArrayChoiceConverter;
use NS\SentinelBundle\Form\IBD\Types\IsolateViable;
use NS\SentinelBundle\Form\IBD\Types\BinaxResult;
use NS\SentinelBundle\Form\IBD\Types\CSFAppearance;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\CXRResult;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification as IBDDischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis as IBDDischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome as IBDDischargeOutcome;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\IntenseSupport;
use NS\SentinelBundle\Form\IBD\Types\IsolateType;
use NS\SentinelBundle\Form\IBD\Types\LatResult;
use NS\SentinelBundle\Form\IBD\Types\VaccinationType;
use NS\SentinelBundle\Form\IBD\Types\NmSerogroup;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\IBD\Types\PCVType;
use NS\SentinelBundle\Form\IBD\Types\SampleType;
use NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome as RVDischargeOutcome;
use NS\SentinelBundle\Form\RotaVirus\Types\VaccinationType as RVVaccinationType;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\SentinelBundle\Form\RotaVirus\Types\Dehydration;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\Rehydration;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\CaseCreationType;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\Types\Role;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\SurveillanceConducted;

/**
 * Description of ArrayChoiceConverterTest
 *
 * @author gnat
 */
class ArrayChoiceConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @param ArrayChoice $obj
     * @dataProvider converterProvider
     */
    public function testArrayChoiceConverterOutOfRange(ArrayChoice $obj)
    {
        $class = get_class($obj);
        $converter = new ArrayChoiceConverter($class);

        $obj->getValues();
        $ret = $converter->__invoke(-12);
        $this->assertEquals($ret->getValue(), ArrayChoice::OUT_OF_RANGE);
        $this->assertTrue($converter->hasMessage());
    }

    /**
     *
     * @param ArrayChoice $obj
     * @dataProvider converterProvider
     */
    public function testArrayChoiceConverter(ArrayChoice $obj)
    {
        $class = get_class($obj);
        $converter = new ArrayChoiceConverter($class);
        $values = $obj->getValues();

        foreach (array_keys($values) as $key) {
            $convertedObj = $converter->__invoke($key);
            $this->assertInstanceOf($class, $convertedObj);
            $this->assertEquals($key, $convertedObj->getValue());
        }

        $convertedObj = $converter->__invoke(' ');
        $this->assertInstanceOf($class, $convertedObj);
        $this->assertEquals(-1, $convertedObj->getValue());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUnknownClass()
    {
        new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\UnknownClass');
    }

    public function converterProvider()
    {
        return [
            ['obj' => new IsolateViable()],
            ['obj' => new BinaxResult()],
            ['obj' => new CSFAppearance()],
            ['obj' => new CXRResult()],
            ['obj' => new CaseStatus()],
            ['obj' => new CaseCreationType()],
            ['obj' => new CultureResult()],
            ['obj' => new Dehydration()],
            ['obj' => new Diagnosis()],
            ['obj' => new IBDDischargeClassification()],
            ['obj' => new IBDDischargeDiagnosis()],
            ['obj' => new IBDDischargeOutcome()],
            ['obj' => new ElisaKit()],
            ['obj' => new ElisaResult()],
            ['obj' => new FourDoses()],
            ['obj' => new Gender()],
            ['obj' => new GenotypeResultG()],
            ['obj' => new GenotypeResultP()],
            ['obj' => new GramStain()],
            ['obj' => new GramStainResult()],
            ['obj' => new HiSerotype()],
            ['obj' => new CaseResult()],
            ['obj' => new IntenseSupport()],
            ['obj' => new IsolateType()],
            ['obj' => new LatResult()],
            ['obj' => new VaccinationReceived()],
            ['obj' => new VaccinationType()],
            ['obj' => new NmSerogroup()],
            ['obj' => new OtherSpecimen()],
            ['obj' => new PCRResult()],
            ['obj' => new PCVType()],
            ['obj' => new PathogenIdentifier()],
            ['obj' => new Rehydration()],
            ['obj' => new Role()],
            ['obj' => new RVDischargeOutcome()],
            ['obj' => new RVVaccinationType()],
            ['obj' => new SampleType()],
            ['obj' => new SerotypeIdentifier()],
            ['obj' => new SpnSerotype()],
            ['obj' => new SurveillanceConducted()],
            ['obj' => new ThreeDoses()],
            ['obj' => new TripleChoice()],
            ['obj' => new VaccinationReceived()],
        ];
    }
}
