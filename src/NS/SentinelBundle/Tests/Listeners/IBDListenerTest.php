<?php

namespace NS\SentinelBundle\Tests\Listeners;

use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Form\Types\CaseStatus;
use \NS\SentinelBundle\Form\Types\CSFAppearance;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\DischargeClassification;
use NS\SentinelBundle\Form\Types\DischargeDiagnosis;
use \NS\SentinelBundle\Form\Types\DischargeOutcome;
use \NS\SentinelBundle\Form\Types\FourDoses;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use \NS\SentinelBundle\Form\Types\MeningitisVaccinationType;
use \NS\SentinelBundle\Form\Types\OtherSpecimen;
use \NS\SentinelBundle\Form\Types\ThreeDoses;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Entity\Listener\IBDListener;

class IBDListenerTest extends \PHPUnit_Framework_TestCase
{
    public function getEventArgs()
    {
        return $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testMinimumRequiredFieldsWithPneunomia()
    {
        $country = new Country('tId', 'Test');
        $country->setTracksPneumonia(true);
        $case    = new IBD();
        $case->setCountry($country);

        $listener = new IBDListener();

        $this->assertCount(35, $listener->getMinimumRequiredFields($case));
    }

    public function testMinimumRequiredFieldsWithoutPneunomia()
    {
        $country = new Country('tId', 'Test');
        $country->setTracksPneumonia(false);
        $case    = new IBD();
        $case->setCountry($country);

        $listener = new IBDListener();

        $this->assertCount(26, $listener->getMinimumRequiredFields($case));
    }

    //============================================================
    // Single complete cases
    public function testSingleMinimumCompleteCaseWithPneunomia()
    {
        $case = new IBD();
        $this->_updateCase($case, $this->getSingleCompleteCaseWithPneunomia());
        $listener = new IBDListener();
        $listener->prePersist($case, $this->getEventArgs());

        $this->assertTrue($case->isComplete(), "New cases are incomplete " . $listener->getIncompleteField($case) . ' ' . $case->getStatus());
    }

    public function testSingleMinimumCompleteCaseWithoutPneunomia()
    {
        $case = new IBD();
        $this->_updateCase($case, $this->getSingleCompleteCaseWithoutPneunomia());
        $listener = new IBDListener();
        $listener->prePersist($case, $this->getEventArgs());

        $this->assertTrue($case->isComplete(), "New cases are incomplete " . $listener->getIncompleteField($case) . ' ' . $case->getStatus());
    }

    //============================================================
    // Incomplete
    /**
     * @depends      testSingleMinimumCompleteCaseWithPneunomia
     * @dataProvider getIncompleteTestDataWithPneunomia
     * @param $data
     */
    public function testCaseIsIncompleteWithPneunomia($data)
    {
        $case = new IBD();
        $this->_updateCase($case, $data);
        $listener = new IBDListener();
        $listener->prePersist($case, $this->getEventArgs());

        $this->assertFalse($case->isComplete(), "New cases are incomplete data removed '" . (isset($data['removed']) ? $data['removed'] : 'no removed') . "'");
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::OPEN);
    }

    /**
     * @depends      testSingleMinimumCompleteCaseWithoutPneunomia
     * @dataProvider getIncompleteTestDataWithoutPneunomia
     * @param $data
     */
    public function testCaseIsIncompleteWithoutPneunomia($data)
    {
        $case = new IBD();
        $this->_updateCase($case, $data);
        $listener = new IBDListener();
        $listener->prePersist($case, $this->getEventArgs());

        $this->assertFalse($case->isComplete(), "New cases are incomplete " . (isset($data['removed']) ? $data['removed'] : 'no removed'));
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::OPEN);
    }

    //============================================================
    // Complete
    /**
     * @depends      testSingleMinimumCompleteCaseWithPneunomia
     * @dataProvider getCompleteCaseWithPneunomiaData
     * @param $data
     */
    public function testCaseIsCompleteWithPneuomia($data)
    {
        $case = new IBD();
        $this->_updateCase($case, $data);
        $listener = new IBDListener();
        $listener->prePersist($case, $this->getEventArgs());

        $this->assertTrue($case->isComplete(), "Cases with pneunomia are complete " . (!$case->isComplete()) ? $listener->getIncompleteField($case) : null);
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::COMPLETE);
    }

    /**
     * @depends      testSingleMinimumCompleteCaseWithPneunomia
     * @dataProvider getCompleteCaseWithoutPneunomiaData
     * @param $data
     */
    public function testCaseIsCompleteWithoutPneunomia($data)
    {
        $case = new IBD();
        $this->_updateCase($case, $data);
        $listener = new IBDListener();
        $listener->prePersist($case, $this->getEventArgs());

        $this->assertTrue($case->isComplete(), "Cases without pneunomia are complete " . (!$case->isComplete()) ? $listener->getIncompleteField($case) : null);
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::COMPLETE);
    }

    //=============================================================
    // data providers

    public function getCompleteCaseWithPneunomiaData()
    {
        $data     = array();
        $complete = $this->getSingleCompleteCaseWithPneunomia();
        $country  = new Country('tId', 'TestCountry');
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
        $country  = new Country('tId', 'TestCountry');
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
        $row                  = $complete;
        $row['setadmDx']      = new Diagnosis(Diagnosis::OTHER);
        $row['setadmDxOther'] = 'null';
        $data[]               = array('data' => $row);

        //dischDx + dischDxOther
        $row                    = $complete;
        $row['setdischDx']      = new DischargeDiagnosis(DischargeDiagnosis::OTHER);
        $row['setdischDxOther'] = 'null';
        $data[]                 = array('data' => $row);

        //meningReceived + meningDoses
        $row                            = $complete;
        $row['setmeningReceived']       = new MeningitisVaccinationReceived(MeningitisVaccinationReceived::YES_CARD);
        $row['setmeningType']           = new MeningitisVaccinationType(MeningitisVaccinationType::ACW135);
        $row['setmeningMostRecentDose'] = new \DateTime();
        $data[]                         = array('data' => $row);

        $doses = new ThreeDoses();
        foreach ($doses->getValues() as $x => $v) {
            //hibReceived + hibDoses
            $row = $complete;
            $row['sethibReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
            $row['sethibDoses'] = new FourDoses($x);
            $data[] = array('data' => $row);

            //pcvReceived + pcvDoses
            $row = $complete;
            $row['setpcvReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
            $row['setpcvDoses'] = new FourDoses($x);
            $data[] = array('data' => $row);
        }

        //csfCollected + related
        $csfAppearance = new CSFAppearance();
        foreach ($csfAppearance->getValues() as $v) {
            $row = $complete;
            $row['setcsfCollected'] = $tripleYes;
            $row['setcsfId'] = 'null';
            $row['setcsfCollectDateTime'] = new \DateTime();
            $row['setcsfAppearance'] = new CSFAppearance($v);
            $data[] = array('data' => $row);
        }

        return $data;
    }

    public function getIncompleteTestDataWithoutPneunomia()
    {
        $data     = array();
        $complete = $this->getSingleCompleteCaseWithoutPneunomia();
        $fields = array(
            'caseId',
            'dob',
            'gender',
            'district',
            'admDate',
            'onsetDate',
            'admDx',
            'antibiotics',
            'menSeizures',
            'menFever',
            'menAltConscious',
            'menInabilityFeed',
            'menNeckStiff',
            'menRash',
            'menFontanelleBulge',
            'menLethargy',
            'hibReceived',
            'pcvReceived',
            'meningReceived',
            'csfCollected',
            'bloodCollected',
            'otherSpecimenCollected',
            'dischOutcome',
            'dischDx',
            'dischClass',
            'cxrDone',
        );
        foreach ($fields as $field) {
            if (isset($complete["set$field"])) {
                $d = $complete;
                unset($d["set$field"]);

                $d['removed'] = $field;
                $data[] = array('data' => $d);
            }
        }

        return $data;
    }

    public function getIncompleteTestDataWithPneunomia()
    {
        $data      = array();
        $tripleYes = new TripleChoice(TripleChoice::YES);
        $complete  = $this->getSingleCompleteCaseWithPneunomia();
        $fields = array(
            'caseId',
            'dob',
            'gender',
            'district',
            'admDate',
            'onsetDate',
            'admDx',
            'antibiotics',
            'menSeizures',
            'menFever',
            'menAltConscious',
            'menInabilityFeed',
            'menNeckStiff',
            'menRash',
            'menFontanelleBulge',
            'menLethargy',
            'hibReceived',
            'pcvReceived',
            'meningReceived',
            'csfCollected',
            'bloodCollected',
            'otherSpecimenCollected',
            'dischOutcome',
            'dischDx',
            'dischClass',
            'cxrDone',
            'pneuDiffBreathe',
            'pneuChestIndraw',
            'pneuCough',
            'pneuCyanosis',
            'pneuStridor',
            'pneuRespRate',
            'pneuVomit',
            'pneuHypothermia',
            'pneuMalnutrition',);
        foreach ($fields as $field) {
            if (isset($complete["set$field"])) {
                $d = $complete;
                unset($d["set$field"]);

                $d['removed'] = $field;
                $data[] = array('data' => $d);
            }
        }

        //admDx + admDxOther
        $d                  = $complete;
        $d['setadmDx']      = new Diagnosis(Diagnosis::OTHER);
        $d['setadmDxOther'] = null;
        $data[]             = array('data' => $d);

        //dischDx + dischDxOther
        $d                    = $complete;
        $d['setdischDx']      = new DischargeDiagnosis(DischargeDiagnosis::OTHER);
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
        $country  = new Country('tId', 'Test Country');
        $country->setTracksPneumonia(true);

        return array(
            'setcountry'              => $country,
            'setcaseId'               => 'blah',
            'setdob'                  => new \DateTime(),
            'setgender'               => new Gender(Gender::MALE),
            'setdistrict'             => 'The District',
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
            'setdischDx'              => new DischargeDiagnosis(DischargeDiagnosis::BACTERIAL_PNEUMONIA),
            'setdischDxOther'         => null,
            'setdischClass'           => new DischargeClassification(DischargeClassification::CONFIRMED_SPN),
            'setCxrDone'              => new TripleChoice(TripleChoice::NO),
        );
    }

    private function getSingleCompleteCaseWithoutPneunomia()
    {
        $tripleNo = new TripleChoice(TripleChoice::NO);
        $country  = new Country('tId', 'Test Country');
        $country->setTracksPneumonia(false);

        return array(
            'setcountry'                => $country,
            'setcaseId'                 => 'blah',
            'setdistrict'               => 'The District',
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
            'setdischDx'                => new DischargeDiagnosis(DischargeDiagnosis::BACTERIAL_PNEUMONIA),
            'setdischDxOther'           => null,
            'setdischClass'             => new DischargeClassification(DischargeClassification::CONFIRMED_SPN),
            'setCxrDone'                => new TripleChoice(TripleChoice::NO),
        );
    }

    private function _updateCase($case, $data)
    {
        foreach ($data as $method => $d) {
            if (!is_null($d) && method_exists($case, $method)) {
                $case->$method($d);
            }
        }
    }
}
