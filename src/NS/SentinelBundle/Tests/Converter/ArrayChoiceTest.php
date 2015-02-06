<?php

namespace NS\SentinelBundle\Tests\Converter;

/**
 * Description of ArrayChoiceTest
 *
 * @author gnat
 */
class ArrayChoiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @param ArrayChoice $obj
     * @dataProvider converterProvider
     * @expectedException \Ddeboer\DataImport\Exception\UnexpectedValueException
     */
    public function testArrayChoice($obj)
    {
        $this->assertTrue(is_object($obj));
        $class     = get_class($obj);
        $converter = new \NS\SentinelBundle\Converter\ArrayChoice($class);
        $values    = $obj->getValues();

        foreach (array_keys($values) as $key)
        {
            $convertedObj = $converter->convert($key);
            $this->assertInstanceOf($class, $convertedObj, sprintf("Converted '%s' to object of class %s", $key, $class));
            $this->assertEquals($key, $convertedObj->getValue());
        }

        $convertedObj = $converter->convert(98);
        $this->assertInstanceOf($class, $convertedObj, sprintf("Converted '%s' to object of class %s", 98, $class));
        $this->assertEquals(99, $convertedObj->getValue());

        $convertedObj = $converter->convert(-12);
    }

    public function converterProvider()
    {
        return array(
            array('obj' => new \NS\SentinelBundle\Form\Types\AlternateTripleChoice()),
            array('obj' => new \NS\SentinelBundle\Form\Types\BinaxResult()),
            array('obj' => new \NS\SentinelBundle\Form\Types\CSFAppearance()),
            array('obj' => new \NS\SentinelBundle\Form\Types\CXRResult()),
            array('obj' => new \NS\SentinelBundle\Form\Types\CaseStatus()),
            array('obj' => new \NS\SentinelBundle\Form\Types\CreateRoles()),
            array('obj' => new \NS\SentinelBundle\Form\Types\CultureResult()),
            array('obj' => new \NS\SentinelBundle\Form\Types\Dehydration()),
            array('obj' => new \NS\SentinelBundle\Form\Types\Diagnosis()),
            array('obj' => new \NS\SentinelBundle\Form\Types\DischargeClassification()),
            array('obj' => new \NS\SentinelBundle\Form\Types\DischargeDiagnosis()),
            array('obj' => new \NS\SentinelBundle\Form\Types\DischargeOutcome()),
            array('obj' => new \NS\SentinelBundle\Form\Types\ElisaKit()),
            array('obj' => new \NS\SentinelBundle\Form\Types\ElisaResult()),
            array('obj' => new \NS\SentinelBundle\Form\Types\FourDoses()),
            array('obj' => new \NS\SentinelBundle\Form\Types\Gender()),
            array('obj' => new \NS\SentinelBundle\Form\Types\GenotypeResultG()),
            array('obj' => new \NS\SentinelBundle\Form\Types\GenotypeResultP()),
            array('obj' => new \NS\SentinelBundle\Form\Types\GramStain()),
            array('obj' => new \NS\SentinelBundle\Form\Types\GramStainOrganism()),
            array('obj' => new \NS\SentinelBundle\Form\Types\HiSerotype()),
            array('obj' => new \NS\SentinelBundle\Form\Types\IBDCaseResult()),
            array('obj' => new \NS\SentinelBundle\Form\Types\IBDIntenseSupport()),
            array('obj' => new \NS\SentinelBundle\Form\Types\IsolateType()),
            array('obj' => new \NS\SentinelBundle\Form\Types\LatResult()),
            array('obj' => new \NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived()),
            array('obj' => new \NS\SentinelBundle\Form\Types\MeningitisVaccinationType()),
            array('obj' => new \NS\SentinelBundle\Form\Types\NmSerogroup()),
            array('obj' => new \NS\SentinelBundle\Form\Types\OtherSpecimen()),
            array('obj' => new \NS\SentinelBundle\Form\Types\PCRResult()),
            array('obj' => new \NS\SentinelBundle\Form\Types\PCVType()),
            array('obj' => new \NS\SentinelBundle\Form\Types\PathogenIdentifier()),
            array('obj' => new \NS\SentinelBundle\Form\Types\Rehydration()),
            array('obj' => new \NS\SentinelBundle\Form\Types\Role()),
            array('obj' => new \NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome()),
            array('obj' => new \NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived()),
            array('obj' => new \NS\SentinelBundle\Form\Types\RotavirusVaccinationType()),
            array('obj' => new \NS\SentinelBundle\Form\Types\SampleType()),
            array('obj' => new \NS\SentinelBundle\Form\Types\SerotypeIdentifier()),
            array('obj' => new \NS\SentinelBundle\Form\Types\SpnSerotype()),
            array('obj' => new \NS\SentinelBundle\Form\Types\SurveillanceConducted()),
            array('obj' => new \NS\SentinelBundle\Form\Types\ThreeDoses()),
            array('obj' => new \NS\SentinelBundle\Form\Types\TripleChoice()),
            array('obj' => new \NS\SentinelBundle\Form\Types\VaccinationReceived()),
            array('obj' => new \NS\SentinelBundle\Form\Types\Volume()),
        );
    }
}