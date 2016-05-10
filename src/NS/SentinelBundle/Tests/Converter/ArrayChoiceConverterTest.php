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
        return array(
            array('obj' => new IsolateViable()),
            array('obj' => new BinaxResult()),
            array('obj' => new CSFAppearance()),
            array('obj' => new CXRResult()),
            array('obj' => new CaseStatus()),
            array('obj' => new CaseCreationType()),
            array('obj' => new CultureResult()),
            array('obj' => new Dehydration()),
            array('obj' => new Diagnosis()),
            array('obj' => new IBDDischargeClassification()),
            array('obj' => new IBDDischargeDiagnosis()),
            array('obj' => new IBDDischargeOutcome()),
            array('obj' => new ElisaKit()),
            array('obj' => new ElisaResult()),
            array('obj' => new FourDoses()),
            array('obj' => new Gender()),
            array('obj' => new GenotypeResultG()),
            array('obj' => new GenotypeResultP()),
            array('obj' => new GramStain()),
            array('obj' => new GramStainResult()),
            array('obj' => new HiSerotype()),
            array('obj' => new CaseResult()),
            array('obj' => new IntenseSupport()),
            array('obj' => new IsolateType()),
            array('obj' => new LatResult()),
            array('obj' => new VaccinationReceived()),
            array('obj' => new VaccinationType()),
            array('obj' => new NmSerogroup()),
            array('obj' => new OtherSpecimen()),
            array('obj' => new PCRResult()),
            array('obj' => new PCVType()),
            array('obj' => new PathogenIdentifier()),
            array('obj' => new Rehydration()),
            array('obj' => new Role()),
            array('obj' => new RVDischargeOutcome()),
            array('obj' => new RVVaccinationType()),
            array('obj' => new SampleType()),
            array('obj' => new SerotypeIdentifier()),
            array('obj' => new SpnSerotype()),
            array('obj' => new SurveillanceConducted()),
            array('obj' => new ThreeDoses()),
            array('obj' => new TripleChoice()),
            array('obj' => new VaccinationReceived()),
        );
    }
}
