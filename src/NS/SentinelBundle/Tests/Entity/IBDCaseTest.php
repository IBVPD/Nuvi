<?php

namespace NS\SentinelBundle\Tests\Entity;

use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Form\Types\CaseStatus;
use \NS\SentinelBundle\Form\Types\CSFAppearance;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\DischargeClassification;
use \NS\SentinelBundle\Form\Types\DischargeOutcome;
use \NS\SentinelBundle\Form\Types\FourDoses;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use \NS\SentinelBundle\Form\Types\MeningitisVaccinationType;
use \NS\SentinelBundle\Form\Types\OtherSpecimen;
use \NS\SentinelBundle\Form\Types\ThreeDoses;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\VaccinationReceived;

/**
 * Description of IBDCaseTest
 *
 * @author gnat
 */
class IBDCaseTest extends \PHPUnit_Framework_TestCase
{
//    public function testCaseRequiresSite()
//    {
//        $sites = $this->entityManager->getRepository('NS\SentinelBundle\Entity\Site')->getChain();
//        $case  = new IBD();
//
//        try
//        {
//            $this->entityManager->persist($case);
//            $this->entityManager->flush();
//            $this->fail("A case requires a site");
//        }
//        catch(\UnexpectedValueException $e)
//        {
//            //$this->success("A case requires a site");
//        }
//
//        try
//        {
//            $case->setSite(array_pop($sites));
//            $this->entityManager->persist($case);
//            $this->entityManager->flush();
//            $this->assertNotNull($case->getId(),"A case requires a site");
//        }
//        catch(\Doctrine\DBAL\DBALException $ex)
//        {
//            //$this->success(true,"A case requires a site");
//        }
//    }
//
//    public function testCaseRequiresCaseId()
//    {
//        $sites = $this->entityManager->getRepository('NS\SentinelBundle\Entity\Site')->getChain();
//        $case  = new IBD();
//        $case->setSite(array_pop($sites));
//
//        try
//        {
//            $this->entityManager->persist($case);
//            $this->entityManager->flush();
//            $this->fail("A case requires a case id");
//        }
//        catch(\Doctrine\DBAL\DBALException $e)
//        {
//            //$this->assertTrue(true,"A case requires a site");
//        }
//    }

    public function testMinimumRequiredFieldsWithPneunomia()
    {
        $country = new Country('Test');
        $country->setTracksPneumonia(true);
        $case    = new IBD();
        $case->setCountry($country);

        $this->assertEquals(34, count($case->getMinimumRequiredFields()));
    }

    public function testMinimumRequiredFieldsWithoutPneunomia()
    {
        $country = new Country('Test');
        $country->setTracksPneumonia(false);
        $case    = new IBD();
        $case->setCountry($country);

        $this->assertEquals(25, count($case->getMinimumRequiredFields()));
    }

    //============================================================
    // Single complete cases
    public function testSingleMinimumCompleteCaseWithPneunomia()
    {
        $case = new IBD();
        $this->_updateCase($case, $this->getSingleCompleteCaseWithPneunomia());
        $case->preUpdateAndPersist();

        $this->assertEquals(34, count($case->getMinimumRequiredFields()));
        $this->assertTrue($case->isComplete(), "New cases are incomplete " . $case->getIncompleteField() . ' ' . $case->getStatus());
    }

    public function testSingleMinimumCompleteCaseWithoutPneunomia()
    {
        $case = new IBD();
        $this->_updateCase($case, $this->getSingleCompleteCaseWithoutPneunomia());
        $case->preUpdateAndPersist();

        $this->assertEquals(25, count($case->getMinimumRequiredFields()));
        $this->assertTrue($case->isComplete(), "New cases are incomplete" . $case->getIncompleteField() . ' ' . $case->getStatus());
    }

    //============================================================
    // Incomplete
    /**
     * @depends testSingleMinimumCompleteCaseWithPneunomia
     * @dataProvider getIncompleteTestDataWithPneunomia
     */
    public function testCaseIsIncompleteWithPneunomia($data)
    {
        $case = new IBD();
        $this->_updateCase($case, $data);
        $case->preUpdateAndPersist();

        $this->assertFalse($case->isComplete(), "New cases are incomplete data removed '" . (isset($data['removed']) ? $data['removed'] : 'no removed') . "'");
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::OPEN);
    }

    /**
     * @depends testSingleMinimumCompleteCaseWithoutPneunomia
     * @dataProvider getIncompleteTestDataWithoutPneunomia
     */
    public function testCaseIsIncompleteWithoutPneunomia($data)
    {
        $case = new IBD();
        $this->_updateCase($case, $data);
        $case->preUpdateAndPersist();

        $this->assertFalse($case->isComplete(), "New cases are incomplete " . (isset($data['removed']) ? $data['removed'] : 'no removed'));
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::OPEN);
    }

    //============================================================
    // Complete
    /**
     * @depends testSingleMinimumCompleteCaseWithPneunomia
     * @dataProvider getCompleteCaseWithPneunomiaData
     */
    public function testCaseIsCompleteWithPneuomia($data)
    {
        $case = new IBD();
        $this->_updateCase($case, $data);
        $case->preUpdateAndPersist();

        $this->assertTrue($case->isComplete(), "Cases with pneunomia are complete " . (!$case->isComplete()) ? $case->getIncompleteField() : null);
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::COMPLETE);
    }

    /**
     * @depends testSingleMinimumCompleteCaseWithPneunomia
     * @dataProvider getCompleteCaseWithoutPneunomiaData
     */
    public function testCaseIsCompleteWithoutPneunomia($data)
    {
        $case = new IBD();
        $this->_updateCase($case, $data);
        $case->preUpdateAndPersist();

        $this->assertTrue($case->isComplete(), "Cases without pneunomia are complete " . (!$case->isComplete()) ? $case->getIncompleteField() : null);
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::COMPLETE);
    }

    //=============================================================
    // data providers

    public function getCompleteCaseWithPneunomiaData()
    {
        $data     = array();
        $complete = $this->getSingleCompleteCaseWithPneunomia();
        $country  = new Country('TestCountry');
        $country->setTracksPneumonia(true);
        $case     = new IBD();
        $case->setCountry($country);

        $data[] = array('data' => $complete);

        return $this->_commonCompleteData($data, $complete);
    }

    public function getCompleteCaseWithoutPneunomiaData()
    {
        $data     = array();
        $complete = $this->getSingleCompleteCaseWithoutPneunomia();
        $country  = new Country('TestCountry');
        $country->setTracksPneumonia(true);
        $case     = new IBD();
        $case->setCountry($country);

        $data[] = array('data' => $complete);
        return $this->_commonCompleteData($data, $complete);
    }

    private function _commonCompleteData($data, $complete)
    {
        $tripleYes = new TripleChoice(TripleChoice::YES);

        //admDx + admDxOther
        $d                  = $complete;
        $d['setadmDx']      = new Diagnosis(Diagnosis::OTHER);
        $d['setadmDxOther'] = 'null';
        $data[]             = array('data' => $d);

        //dischDx + dischDxOther
        $d                    = $complete;
        $d['setdischDx']      = new Diagnosis(Diagnosis::OTHER);
        $d['setdischDxOther'] = 'null';
        $data[]               = array('data' => $d);

        //meningReceived + meningDoses
        $d                            = $complete;
        $d['setmeningReceived']       = new MeningitisVaccinationReceived(MeningitisVaccinationReceived::YES_CARD);
        $d['setmeningType']           = new MeningitisVaccinationType(MeningitisVaccinationType::ACW135);
        $d['setmeningMostRecentDose'] = new \DateTime();
        $data[]                       = array('data' => $d);

        $doses = new ThreeDoses();
        foreach ($doses->getValues() as $x => $v)
        {
            //hibReceived + hibDoses
            $d                   = $complete;
            $d['sethibReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
            $d['sethibDoses']    = new FourDoses($x);
            $data[]              = array('data' => $d);

            //pcvReceived + pcvDoses
            $d                   = $complete;
            $d['setpcvReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
            $d['setpcvDoses']    = new ThreeDoses($x);
            $data[]              = array('data' => $d);
        }

        //csfCollected + related
        $a = new CSFAppearance();
        foreach ($a->getValues() as $v)
        {
            $d                          = $complete;
            $d['setcsfCollected']       = $tripleYes;
            $d['setcsfId']              = 'null';
            $d['setcsfCollectDateTime'] = new \DateTime();
            $d['setcsfAppearance']      = new CSFAppearance($v);
            $data[]                     = array('data' => $d);
        }

        return $data;
    }

    public function getIncompleteTestDataWithoutPneunomia()
    {
        $data     = array();
        $complete = $this->getSingleCompleteCaseWithoutPneunomia();
        $country  = new Country('TestCountry');
        $country->setTracksPneumonia(false);
        $case     = new IBD();
        $case->setCountry($country);

        foreach ($case->getMinimumRequiredFields() as $field)
        {
            if (isset($complete["set$field"]))
            {
                $d = $complete;
                unset($d["set$field"]);

                $d['removed'] = $field;
                $data[]       = array('data' => $d);
            }
        }

        return $data;
    }

    public function getIncompleteTestDataWithPneunomia()
    {
        $data      = array();
        $tripleYes = new TripleChoice(TripleChoice::YES);
        $complete  = $this->getSingleCompleteCaseWithPneunomia();
        $country   = new Country('TestCountry');
        $country->setTracksPneumonia(true);
        $case      = new IBD();
        $case->setCountry($country);

        foreach ($case->getMinimumRequiredFields() as $field)
        {
            if (isset($complete["set$field"]))
            {
                $d = $complete;
                unset($d["set$field"]);

                $d['removed'] = $field;
                $data[]       = array('data' => $d);
            }
        }

        //admDx + admDxOther
        $d                  = $complete;
        $d['setadmDx']      = new Diagnosis(Diagnosis::OTHER);
        $d['setadmDxOther'] = null;
        $data[]             = array('data' => $d);

        //dischDx + dischDxOther
        $d                    = $complete;
        $d['setdischDx']      = new Diagnosis(Diagnosis::OTHER);
        $d['setdischDxOther'] = null;
        $data[]               = array('data' => $d);

        //hibReceived + hibDoses
        $d                   = $complete;
        $d['sethibReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $d['sethibDoses']    = null;
        $data[]              = array('data' => $d);

        //pcvReceived + pcvDoses
        $d                   = $complete;
        $d['setpcvReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $d['setpcvDoses']    = null;
        $data[]              = array('data' => $d);

        //meningReceived + meningDoses
        $d                      = $complete;
        $d['setmeningReceived'] = new MeningitisVaccinationReceived(MeningitisVaccinationReceived::YES_CARD);
        $d['setmeningType']     = null;
        $data[]                 = array('data' => $d);

        //csfCollected + related
        $d                    = $complete;
        $d['setcsfCollected'] = $tripleYes;
        $d['setcsfId']        = null;
        $data[]               = array('data' => $d);

        $d                    = $complete;
        $d['setcsfCollected'] = $tripleYes;
        $d['setcsfId']        = '';
        $data[]               = array('data' => $d);

        $d                          = $complete;
        $d['setcsfCollected']       = $tripleYes;
        $d['setcsfCollectDateTime'] = null;
        $data[]                     = array('data' => $d);

        $d                    = $complete;
        $d['setcsfCollected'] = $tripleYes;
        $d['csfAppearance']   = null;
        $data[]               = array('data' => $d);

        $d                    = $complete;
        $d['setcsfCollected'] = $tripleYes;
        $d['csfAppearance']   = new CSFAppearance();
        $data[]               = array('data' => $d);

        return $data;
    }

    private function getSingleCompleteCaseWithPneunomia()
    {
        $tripleNo = new TripleChoice(TripleChoice::NO);
        $country  = new Country('Test Country');
        $country->setTracksPneumonia(true);

        return array(
            'setcountry'              => $country,
            'setcaseId'               => 'blah',
            'setdob'                  => new \DateTime(),
            'setgender'               => new Gender(Gender::MALE),
            'setadmDate'              => new \DateTime(),
            'setonsetDate'            => new \DateTime(),
            'setadmDx'                => new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            'setadmDxOther'           => null,
            'setantibiotics'          => $tripleNo,
            'setmenSeizures'          => $tripleNo,
            'setmenFever'             => $tripleNo,
            'setmenAltConscious'      => $tripleNo,
            'setmenInabilityFeed'     => $tripleNo,
            'setmenNeckStiff'         => $tripleNo,
            'setmenRash'              => $tripleNo,
            'setmenFontanelleBulge'   => $tripleNo,
            'setmenLethargy'          => $tripleNo,
            'setpneuDiffBreathe'      => $tripleNo,
            'setpneuChestIndraw'      => $tripleNo,
            'setpneuCough'            => $tripleNo,
            'setpneuCyanosis'         => $tripleNo,
            'setpneuStridor'          => $tripleNo,
            'setpneuRespRate'         => 3,
            'setpneuVomit'            => $tripleNo,
            'setpneuHypothermia'      => $tripleNo,
            'setpneuMalnutrition' => $tripleNo,
            'setotherspecimencollected' => new OtherSpecimen(OtherSpecimen::NONE),
            'sethibReceived'            => new VaccinationReceived(VaccinationReceived::UNKNOWN),
            'sethibDoses'             => null,
            'setpcvReceived'            => new VaccinationReceived(VaccinationReceived::NO),
            'setpcvDoses'             => null,
            'setmeningReceived'       => new MeningitisVaccinationReceived(MeningitisVaccinationReceived::NO),
            'setmeningType'           => null,
            'setmeningMostRecentDose' => null,
            'setcsfCollected'         => $tripleNo,
            'setcsfId'                => null,
            'setcsfCollectDateTime'   => null,
            'setcsfAppearance'        => null,
            'setbloodCollected'       => $tripleNo,
            'setbloodId'              => null,
            'setdischOutcome'         => new DischargeOutcome(DischargeOutcome::DISCHARGED_ALIVE_WITHOUT_SEQUELAE),
            'setdischDx'              => new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            'setdischDxOther'         => null,
            'setdischClass'           => new DischargeClassification(DischargeClassification::CONFIRMED_SPN),
            'setCxrDone'              => new TripleChoice(TripleChoice::NO),
        );
    }

    private function getSingleCompleteCaseWithoutPneunomia()
    {
        $tripleNo = new TripleChoice(TripleChoice::NO);
        $country  = new Country('Test Country');
        $country->setTracksPneumonia(false);

        return array(
                    'setcountry'                => $country,
            'setcaseId'                 => 'blah',
            'setdob'                    => new \DateTime(),
            'setgender'                 => new Gender(Gender::MALE),
            'setadmDate'                => new \DateTime(),
            'setonsetDate'              => new \DateTime(),
            'setadmDx'                  => new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            'setadmDxOther'             => null,
            'setantibiotics'            => $tripleNo,
            'setmenSeizures'            => $tripleNo,
            'setmenFever'               => $tripleNo,
            'setmenAltConscious'        => $tripleNo,
            'setmenInabilityFeed'       => $tripleNo,
            'setmenNeckStiff'           => $tripleNo,
            'setmenRash'                => $tripleNo,
            'setmenFontanelleBulge'     => $tripleNo,
            'setmenLethargy'            => $tripleNo,
            'sethibReceived'            => new VaccinationReceived(VaccinationReceived::UNKNOWN),
            'sethibDoses'               => null,
            'setpcvReceived'            => new VaccinationReceived(VaccinationReceived::UNKNOWN),
            'setpcvDoses'               => null,
            'setmeningReceived'         => new MeningitisVaccinationReceived(MeningitisVaccinationReceived::NO),
            'setmeningType'             => null,
            'setmeningMostRecentDose'   => null,
            'setcsfCollected'           => $tripleNo,
//                    'setcsfId'             => null,
            'setcsfCollectDateTime'     => null,
            'setcsfAppearance'          => null,
            'setbloodCollected'         => $tripleNo,
            'setOtherSpecimenCollected' => new OtherSpecimen(OtherSpecimen::NONE),
            'setbloodId'                => null,
            'setdischOutcome'           => new DischargeOutcome(DischargeOutcome::DISCHARGED_ALIVE_WITHOUT_SEQUELAE),
            'setdischDx'                => new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            'setdischDxOther'           => null,
            'setdischClass'             => new DischargeClassification(DischargeClassification::CONFIRMED_SPN),
            'setCxrDone'                => new TripleChoice(TripleChoice::NO),
        );
    }

    private function _updateCase($case, $data)
    {
        foreach ($data as $method => $d)
        {
            if (!is_null($d) && method_exists($case, $method))
                $case->$method($d);
        }
    }
}
