<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\UtilBundle\Form\Types\ArrayChoice;
use NS\SentinelBundle\Converter\ArrayChoiceConverter;
use NS\SentinelBundle\Form\Types\IsolateViable;
use NS\SentinelBundle\Form\Types\BinaxResult;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\CreateRoles;
use NS\SentinelBundle\Form\Types\CSFAppearance;
use NS\SentinelBundle\Form\Types\CultureResult;
use NS\SentinelBundle\Form\Types\CXRResult;
use NS\SentinelBundle\Form\Types\Dehydration;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Form\Types\DischargeClassification;
use NS\SentinelBundle\Form\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\ElisaKit;
use NS\SentinelBundle\Form\Types\ElisaResult;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\GenotypeResultG;
use NS\SentinelBundle\Form\Types\GenotypeResultP;
use NS\SentinelBundle\Form\Types\GramStain;
use NS\SentinelBundle\Form\Types\GramStainResult;
use NS\SentinelBundle\Form\Types\HiSerotype;
use NS\SentinelBundle\Form\Types\IBDCaseResult;
use NS\SentinelBundle\Form\Types\IBDIntenseSupport;
use NS\SentinelBundle\Form\Types\IsolateType;
use NS\SentinelBundle\Form\Types\LatResult;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationType;
use NS\SentinelBundle\Form\Types\NmSerogroup;
use NS\SentinelBundle\Form\Types\OtherSpecimen;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\PCRResult;
use NS\SentinelBundle\Form\Types\PCVType;
use NS\SentinelBundle\Form\Types\Rehydration;
use NS\SentinelBundle\Form\Types\Role;
use NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationType;
use NS\SentinelBundle\Form\Types\SampleType;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\SpnSerotype;
use NS\SentinelBundle\Form\Types\SurveillanceConducted;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;

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
     * @param string $name
     * @dataProvider converterProvider
     */
    public function testArrayChoiceConverterOutOfRange(ArrayChoice $obj, $name)
    {
        $class = get_class($obj);
        $converter = new ArrayChoiceConverter($class);
        $this->assertEquals($name, $converter->getName());
        $obj->getValues();
        $ret = $converter->__invoke(-12);
        $this->assertEquals($ret->getValue(), ArrayChoice::OUT_OF_RANGE);
        $this->assertTrue($converter->hasMessage());
    }

    /**
     *
     * @param ArrayChoice $obj
     * @param string $name
     * @dataProvider converterProvider
     */
    public function testArrayChoiceConverter(ArrayChoice $obj, $name)
    {
        $class = get_class($obj);
        $converter = new ArrayChoiceConverter($class);
        $this->assertEquals($name, $converter->getName());
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
            array(
                'obj' => new IsolateViable(),
                'name' => 'IsolateViable'),
            array(
                'obj' => new BinaxResult(),
                'name' => 'BinaxResult',),
            array(
                'obj' => new CSFAppearance(),
                'name' => 'CSFAppearance',),
            array(
                'obj' => new CXRResult(),
                'name' => 'CXRResult',),
            array(
                'obj' => new CaseStatus(),
                'name' => 'CaseStatus',),
            array(
                'obj' => new CreateRoles(),
                'name' => 'CreateRoles',),
            array(
                'obj' => new CultureResult(),
                'name' => 'CultureResult',),
            array(
                'obj' => new Dehydration(),
                'name' => 'Dehydration',),
            array(
                'obj' => new Diagnosis(),
                'name' => 'Diagnosis',),
            array(
                'obj' => new DischargeClassification(),
                'name' => 'DischargeClassification',),
            array(
                'obj' => new DischargeDiagnosis(),
                'name' => 'DischargeDiagnosis',),
            array(
                'obj' => new DischargeOutcome(),
                'name' => 'DischargeOutcome',),
            array(
                'obj' => new ElisaKit(),
                'name' => 'ElisaKit',),
            array(
                'obj' => new ElisaResult(),
                'name' => 'ElisaResult',),
            array(
                'obj' => new FourDoses(),
                'name' => 'FourDoses',),
            array(
                'obj' => new Gender(),
                'name' => 'Gender',),
            array(
                'obj' => new GenotypeResultG(),
                'name' => 'GenotypeResultG',),
            array(
                'obj' => new GenotypeResultP(),
                'name' => 'GenotypeResultP',),
            array(
                'obj' => new GramStain(),
                'name' => 'GramStain',),
            array(
                'obj' => new GramStainResult(),
                'name' => 'GramStainResult'),
            array(
                'obj' => new HiSerotype(),
                'name' => 'HiSerotype'),
            array(
                'obj' => new IBDCaseResult(),
                'name' => 'IBDCaseResult'),
            array(
                'obj' => new IBDIntenseSupport(),
                'name' => 'IBDIntenseSupport'),
            array(
                'obj' => new IsolateType(),
                'name' => 'IsolateType'),
            array(
                'obj' => new LatResult(),
                'name' => 'LatResult'),
            array(
                'obj' => new MeningitisVaccinationReceived(),
                'name' => 'MeningitisVaccinationReceived'),
            array(
                'obj' => new MeningitisVaccinationType(),
                'name' => 'MeningitisVaccinationType'),
            array(
                'obj' => new NmSerogroup(),
                'name' => 'NmSerogroup'),
            array(
                'obj' => new OtherSpecimen(),
                'name' => 'OtherSpecimen'),
            array(
                'obj' => new PCRResult(),
                'name' => 'PCRResult'),
            array(
                'obj' => new PCVType(),
                'name' => 'PCVType'),
            array(
                'obj' => new PathogenIdentifier(),
                'name' => 'PathogenIdentifier'),
            array(
                'obj' => new Rehydration(),
                'name' => 'Rehydration'),
            array(
                'obj' => new Role(),
                'name' => 'Role'),
            array(
                'obj' => new RotavirusDischargeOutcome(),
                'name' => 'RotavirusDischargeOutcome'),
            array(
                'obj' => new RotavirusVaccinationReceived(),
                'name' => 'RotavirusVaccinationReceived',),
            array(
                'obj' => new RotavirusVaccinationType(),
                'name' => 'RotavirusVaccinationType'),
            array(
                'obj' => new SampleType(),
                'name' => 'SampleType'),
            array(
                'obj' => new SerotypeIdentifier(),
                'name' => 'SerotypeIdentifier'),
            array(
                'obj' => new SpnSerotype(),
                'name' => 'SpnSerotype'),
            array(
                'obj' => new SurveillanceConducted(),
                'name' => 'SurveillanceConducted'),
            array(
                'obj' => new ThreeDoses(),
                'name' => 'ThreeDoses'),
            array(
                'obj' => new TripleChoice(),
                'name' => 'TripleChoice'),
            array(
                'obj' => new VaccinationReceived(),
                'name' => 'VaccinationReceived'),
        );
    }

}
