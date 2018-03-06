<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\UtilBundle\Form\Types\ArrayChoice;
use NS\SentinelBundle\Converter\ArrayChoiceConverter;
use NS\SentinelBundle\Form;
//use NS\SentinelBundle\Form\Meningitis;
//use NS\SentinelBundle\Form\Pneumonia;
//use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome as RVDischargeOutcome;
//use NS\SentinelBundle\Form\RotaVirus\Types\VaccinationType as RVVaccinationType;
//use NS\SentinelBundle\Form\Types\CaseStatus;
//use NS\SentinelBundle\Form\Types\CaseCreationType;
//use NS\SentinelBundle\Form\Types\FourDoses;
//use NS\SentinelBundle\Form\Types\Gender;
//use NS\SentinelBundle\Form\Types\VaccinationReceived;
//use NS\SentinelBundle\Form\Types\Role;
//use NS\SentinelBundle\Form\Types\ThreeDoses;
//use NS\SentinelBundle\Form\Types\TripleChoice;
//use NS\SentinelBundle\Form\Types\SurveillanceConducted;

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
        $converter = new ArrayChoiceConverter($class,'nothing');

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
        $converter = new ArrayChoiceConverter($class,'nothing');
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
        new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\UnknownClass','nothing');
    }

    public function converterProvider()
    {
        return [
            ['obj' => new Form\Types\VaccinationReceived()],
            ['obj' => new Form\Types\CaseStatus()],
            ['obj' => new Form\Types\FourDoses()],
            ['obj' => new Form\Types\Gender()],
            ['obj' => new Form\Types\CaseCreationType()],
            ['obj' => new Form\Types\Role()],
            ['obj' => new Form\Types\SurveillanceConducted()],
            ['obj' => new Form\Types\ThreeDoses()],
            ['obj' => new Form\Types\TripleChoice()],

            ['obj' => new Form\IBD\Types\IsolateViable()],
            ['obj' => new Form\IBD\Types\BinaxResult()],
//            ['obj' => new Form\IBD\Types\CSFAppearance()],
//            ['obj' => new Form\IBD\Types\CXRResult()],
            ['obj' => new Form\IBD\Types\CultureResult()],
            ['obj' => new Form\IBD\Types\Diagnosis()],
            ['obj' => new Form\IBD\Types\DischargeClassification()],
            ['obj' => new Form\IBD\Types\DischargeDiagnosis()],
            ['obj' => new Form\IBD\Types\DischargeOutcome()],
            ['obj' => new Form\IBD\Types\GramStain()],
            ['obj' => new Form\IBD\Types\GramStainResult()],
            ['obj' => new Form\IBD\Types\HiSerotype()],
            ['obj' => new Form\IBD\Types\CaseResult()],
            ['obj' => new Form\IBD\Types\IntenseSupport()],
            ['obj' => new Form\IBD\Types\IsolateType()],
            ['obj' => new Form\IBD\Types\LatResult()],
            ['obj' => new Form\IBD\Types\VaccinationType()],
            ['obj' => new Form\IBD\Types\NmSerogroup()],
            ['obj' => new Form\IBD\Types\OtherSpecimen()],
            ['obj' => new Form\IBD\Types\PCRResult()],
            ['obj' => new Form\IBD\Types\PCVType()],
            ['obj' => new Form\IBD\Types\PathogenIdentifier()],
            ['obj' => new Form\IBD\Types\SampleType()],
            ['obj' => new Form\IBD\Types\SerotypeIdentifier()],
            ['obj' => new Form\IBD\Types\SpnSerotype()],

            ['obj' => new Form\RotaVirus\Types\Dehydration()],
            ['obj' => new Form\RotaVirus\Types\ElisaKit()],
            ['obj' => new Form\RotaVirus\Types\ElisaResult()],
            ['obj' => new Form\RotaVirus\Types\GenotypeResultG()],
            ['obj' => new Form\RotaVirus\Types\GenotypeResultP()],
            ['obj' => new Form\RotaVirus\Types\Rehydration()],
            ['obj' => new Form\RotaVirus\Types\DischargeOutcome()],
            ['obj' => new Form\RotaVirus\Types\VaccinationType()],

            ['obj' => new Form\Meningitis\Types\CSFAppearance()],
        ];
    }
}
