<?php

namespace NS\SentinelBundle\Tests\Listeners;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Meningitis\Types\CSFAppearance;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use NS\SentinelBundle\Form\IBD\Types\VaccinationType;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Entity\Listener\IBDListener;
use NS\SentinelBundle\Validators\Cache\CachedValidations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IBDListenerTest extends TestCase
{
    /** @var CachedValidations|MockObject */
    private $cachedValidator;

    /** @var IBDListener */
    private $listener;

    public function setUp()
    {
        $this->cachedValidator = $this->createMock(CachedValidations::class);
        $this->listener        = new IBDListener($this->cachedValidator);
    }

    public function testMinimumRequiredFieldsWithPneumonia(): void
    {
        $country = new Country('tId', 'Test');
        $country->setTracksPneumonia(true);
        $case = new IBD();
        $case->setCountry($country);

        $this->assertCount(35, $this->listener->getMinimumRequiredFields($case));
    }

    public function testMinimumRequiredFieldsWithoutPneumonia(): void
    {
        $country = new Country('tId', 'Test');
        $country->setTracksPneumonia(false);
        $case = new IBD();
        $case->setRegion(new Region('TCode','Test Region'));
        $case->setCountry($country);

        $this->assertCount(26, $this->listener->getMinimumRequiredFields($case));
    }

    //============================================================
    // Single complete cases
    public function testSingleMinimumCompleteCaseWithPneumonia(): void
    {
        $case = new IBD();
        $case->setRegion(new Region('TCode','Test Region'));
        $this->_updateCase($case, $this->getSingleCompleteCaseWithPneumonia());
        $this->listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertTrue($case->isComplete(), sprintf('New cases are incomplete %s %s', $this->listener->getIncompleteField($case), $case->getStatus()));
    }

    public function testSingleMinimumCompleteCaseWithoutPneumonia(): void
    {
        $case = new IBD();
        $case->setRegion(new Region('TCode','Test Region'));
        $this->_updateCase($case, $this->getSingleCompleteCaseWithoutPneumonia());
        $this->listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertTrue($case->isComplete(), sprintf('New cases are incomplete %s %s', $this->listener->getIncompleteField($case), $case->getStatus()));
    }

    //============================================================
    // Incomplete
    /**
     * @depends      testSingleMinimumCompleteCaseWithPneumonia
     * @dataProvider getIncompleteTestDataWithPneumonia
     *
     * @param $data
     */
    public function testCaseIsIncompleteWithPneumonia($data): void
    {
        $case = new IBD();
        $case->setRegion(new Region('TCode', 'Test Region'));
        $this->_updateCase($case, $data);
        $this->listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertFalse($case->isComplete(), sprintf("New cases are incomplete data removed '%s'", $data['removed'] ?? 'no removed'));
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::OPEN);
    }

    /**
     * @depends      testSingleMinimumCompleteCaseWithoutPneumonia
     * @dataProvider getIncompleteTestDataWithoutPneumonia
     *
     * @param $data
     */
    public function testCaseIsIncompleteWithoutPneumonia($data): void
    {
        $case = new IBD();
        $case->setRegion(new Region('TCode', 'Test Region'));
        $this->_updateCase($case, $data);
        $this->listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertFalse($case->isComplete(), sprintf('New cases are incomplete %s', $data['removed'] ?? 'no removed'));
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::OPEN);
    }

    //============================================================
    // Complete
    /**
     * @depends      testSingleMinimumCompleteCaseWithPneumonia
     * @dataProvider getCompleteCaseWithPneumoniaData
     *
     * @param $data
     */
    public function testCaseIsCompleteWithPneuomia($data): void
    {
        $case = new IBD();
        $case->setRegion(new Region('TCode', 'Test Region'));
        $this->_updateCase($case, $data);
        $this->listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertTrue($case->isComplete(), 'Cases with pneunomia are complete ' . (!$case->isComplete() ? $this->listener->getIncompleteField($case) : null));
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::COMPLETE);
    }

    /**
     * @depends      testSingleMinimumCompleteCaseWithPneumonia
     * @dataProvider getCompleteCaseWithoutPneumoniaData
     *
     * @param $data
     */
    public function testCaseIsCompleteWithoutPneumonia($data): void
    {
        $case = new IBD();
        $case->setRegion(new Region('TCode','Test Region'));
        $this->_updateCase($case, $data);
        $this->listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertTrue($case->isComplete(), 'Cases without pneunomia are complete ' . (!$case->isComplete() ? $this->listener->getIncompleteField($case) : null));
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::COMPLETE);
    }

    //=============================================================
    // data providers

    public function getCompleteCaseWithPneumoniaData()
    {
        $data     = [];
        $complete = $this->getSingleCompleteCaseWithPneumonia();
        $country  = new Country('tId', 'TestCountry');
        $country->setTracksPneumonia(true);
        $country->setRegion(new Region('TCode','Test Region'));
        $case = new IBD();
        $case->setCountry($country);

        $data[] = ['data' => $complete];

        return $this->_commonCompleteData($data, $complete);
    }

    public function getCompleteCaseWithoutPneumoniaData()
    {
        $data     = [];
        $complete = $this->getSingleCompleteCaseWithoutPneumonia();
        $country  = new Country('tId', 'TestCountry');
        $country->setTracksPneumonia(true);
        $country->setRegion(new Region('TCode','Test Region'));
        $case = new IBD();
        $case->setCountry($country);

        $data[] = ['data' => $complete];
        return $this->_commonCompleteData($data, $complete);
    }

    private function _commonCompleteData($data, $complete)
    {
        $tripleYes = new TripleChoice(TripleChoice::YES);

        //admDx + admDxOther
        $row                  = $complete;
        $row['setadmDx']      = new Diagnosis(Diagnosis::OTHER);
        $row['setadmDxOther'] = 'null';
        $data[]               = ['data' => $row];

        //dischDx + dischDxOther
        $row                    = $complete;
        $row['setdischDx']      = new DischargeDiagnosis(DischargeDiagnosis::OTHER);
        $row['setdischDxOther'] = 'null';
        $data[]                 = ['data' => $row];

        //meningReceived + meningDoses
        $row                      = $complete;
        $row['setmeningReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $row['setmeningType']     = new VaccinationType(VaccinationType::ACW135);
        $row['setmeningDate']     = new DateTime();
        $data[]                   = ['data' => $row];

        $doses = new ThreeDoses();
        foreach ($doses->getValues() as $x => $v) {
            //hibReceived + hibDoses
            $row                   = $complete;
            $row['sethibReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
            $row['sethibDoses']    = new FourDoses($x);
            $data[]                = ['data' => $row];

            //pcvReceived + pcvDoses
            $row                   = $complete;
            $row['setpcvReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
            $row['setpcvDoses']    = new FourDoses($x);
            $data[]                = ['data' => $row];
        }

        //csfCollected + related
        $csfAppearance = new CSFAppearance();
        foreach ($csfAppearance->getValues() as $v) {
            $row                      = $complete;
            $row['setcsfCollected']   = $tripleYes;
            $row['setcsfId']          = 'null';
            $row['setcsfCollectDate'] = new DateTime();
            $row['setcsfCollectTime'] = $row['setcsfCollectDate'];
            $row['setcsfAppearance']  = new CSFAppearance($v);
            $data[]                   = ['data' => $row];
        }

        return $data;
    }

    public function getIncompleteTestDataWithoutPneumonia(): array
    {
        $data     = [];
        $complete = $this->getSingleCompleteCaseWithoutPneumonia();
        $fields   = [
            'caseId',
            'birthdate',
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
        ];
        foreach ($fields as $field) {
            if (isset($complete["set$field"])) {
                $d = $complete;
                unset($d["set$field"]);

                $d['removed'] = $field;
                $data[]       = ['data' => $d];
            }
        }

        return $data;
    }

    public function getIncompleteTestDataWithPneumonia(): array
    {
        $data      = [];
        $tripleYes = new TripleChoice(TripleChoice::YES);
        $complete  = $this->getSingleCompleteCaseWithPneumonia();
        $fields    = [
            'caseId',
            'birthdate',
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
            'pneuMalnutrition',];
        foreach ($fields as $field) {
            if (isset($complete["set$field"])) {
                $d = $complete;
                unset($d["set$field"]);

                $d['removed'] = $field;
                $data[]       = ['data' => $d];
            }
        }

        //admDx + admDxOther
        $d                  = $complete;
        $d['setadmDx']      = new Diagnosis(Diagnosis::OTHER);
        $d['setadmDxOther'] = null;
        $data[]             = ['data' => $d];

        //dischDx + dischDxOther
        $d                    = $complete;
        $d['setdischDx']      = new DischargeDiagnosis(DischargeDiagnosis::OTHER);
        $d['setdischDxOther'] = null;
        $data[]               = ['data' => $d];

        //hibReceived + hibDoses
        $d                   = $complete;
        $d['sethibReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $d['sethibDoses']    = null;
        $data[]              = ['data' => $d];

        //pcvReceived + pcvDoses
        $d                   = $complete;
        $d['setpcvReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $d['setpcvDoses']    = null;
        $data[]              = ['data' => $d];

        //meningReceived + meningDoses
        $d                      = $complete;
        $d['setmeningReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $d['setmeningType']     = null;
        $data[]                 = ['data' => $d];

        //csfCollected + related
        $d                    = $complete;
        $d['setcsfCollected'] = $tripleYes;
        $d['setcsfId']        = null;
        $data[]               = ['data' => $d];

        $d                    = $complete;
        $d['setcsfCollected'] = $tripleYes;
        $d['setcsfId']        = '';
        $data[]               = ['data' => $d];

        $d                          = $complete;
        $d['setcsfCollected']       = $tripleYes;
        $d['setcsfCollectDateTime'] = null;
        $data[]                     = ['data' => $d];

        $d                    = $complete;
        $d['setcsfCollected'] = $tripleYes;
        $d['csfAppearance']   = null;
        $data[]               = ['data' => $d];

        $d                    = $complete;
        $d['setcsfCollected'] = $tripleYes;
        $d['csfAppearance']   = new CSFAppearance();
        $data[]               = ['data' => $d];

        return $data;
    }

    private function getSingleCompleteCaseWithPneumonia(): array
    {
        $tripleNo = new TripleChoice(TripleChoice::NO);
        $country  = new Country('tId', 'Test Country');
        $country->setTracksPneumonia(true);
        $country->setRegion(new Region('TCode', 'Test Region'));

        return [
            'setcountry' => $country,
            'setcaseId' => 'blah',
            'setbirthdate' => new DateTime(),
            'setgender' => new Gender(Gender::MALE),
            'setdistrict' => 'The District',
            'setadmDate' => new DateTime(),
            'setonsetDate' => new DateTime(),
            'setadmDx' => new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            'setadmDxOther' => null,
            'setantibiotics' => $tripleNo,
            'setmenSeizures' => $tripleNo,
            'setmenFever' => $tripleNo,
            'setmenAltConscious' => $tripleNo,
            'setmenInabilityFeed' => $tripleNo,
            'setmenNeckStiff' => $tripleNo,
            'setmenRash' => $tripleNo,
            'setmenFontanelleBulge' => $tripleNo,
            'setmenLethargy' => $tripleNo,
            'setpneuDiffBreathe' => $tripleNo,
            'setpneuChestIndraw' => $tripleNo,
            'setpneuCough' => $tripleNo,
            'setpneuCyanosis' => $tripleNo,
            'setpneuStridor' => $tripleNo,
            'setpneuRespRate' => 3,
            'setpneuVomit' => $tripleNo,
            'setpneuHypothermia' => $tripleNo,
            'setpneuMalnutrition' => $tripleNo,
            'setotherspecimencollected' => new OtherSpecimen(OtherSpecimen::NONE),
            'sethibReceived' => new VaccinationReceived(VaccinationReceived::UNKNOWN),
            'sethibDoses' => null,
            'setpcvReceived' => new VaccinationReceived(VaccinationReceived::NO),
            'setpcvDoses' => null,
            'setmeningReceived' => new VaccinationReceived(VaccinationReceived::NO),
            'setmeningType' => null,
            'setmeningDate' => null,
            'setcsfCollected' => $tripleNo,
            'setcsfId' => null,
            'setcsfCollectDate' => null,
            'setcsfCollectTime' => null,
            'setcsfAppearance' => null,
            'setbloodCollected' => $tripleNo,
            'setbloodId' => null,
            'setdischOutcome' => new DischargeOutcome(DischargeOutcome::DISCHARGED_ALIVE_WITHOUT_SEQUELAE),
            'setdischDx' => new DischargeDiagnosis(DischargeDiagnosis::BACTERIAL_PNEUMONIA),
            'setdischDxOther' => null,
            'setdischClass' => new DischargeClassification(DischargeClassification::CONFIRMED_SPN),
            'setCxrDone' => new TripleChoice(TripleChoice::NO),
        ];
    }

    private function getSingleCompleteCaseWithoutPneumonia(): array
    {
        $tripleNo = new TripleChoice(TripleChoice::NO);
        $country  = new Country('tId', 'Test Country');
        $country->setRegion(new Region('RCode', 'Test Region'));
        $country->setTracksPneumonia(false);

        return [
            'setcountry' => $country,
            'setcaseId' => 'blah',
            'setdistrict' => 'The District',
            'setbirthdate' => new DateTime(),
            'setgender' => new Gender(Gender::MALE),
            'setadmDate' => new DateTime(),
            'setonsetDate' => new DateTime(),
            'setadmDx' => new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            'setadmDxOther' => null,
            'setantibiotics' => $tripleNo,
            'setmenSeizures' => $tripleNo,
            'setmenFever' => $tripleNo,
            'setmenAltConscious' => $tripleNo,
            'setmenInabilityFeed' => $tripleNo,
            'setmenNeckStiff' => $tripleNo,
            'setmenRash' => $tripleNo,
            'setmenFontanelleBulge' => $tripleNo,
            'setmenLethargy' => $tripleNo,
            'sethibReceived' => new VaccinationReceived(VaccinationReceived::UNKNOWN),
            'sethibDoses' => null,
            'setpcvReceived' => new VaccinationReceived(VaccinationReceived::UNKNOWN),
            'setpcvDoses' => null,
            'setmeningReceived' => new VaccinationReceived(VaccinationReceived::NO),
            'setmeningType' => null,
            'setmeningDate' => null,
            'setcsfCollected' => $tripleNo,
//                    'setcsfId'             => null,
            'setcsfCollectDate' => null,
            'setcsfCollectTime' => null,
            'setcsfAppearance' => null,
            'setbloodCollected' => $tripleNo,
            'setOtherSpecimenCollected' => new OtherSpecimen(OtherSpecimen::NONE),
            'setbloodId' => null,
            'setdischOutcome' => new DischargeOutcome(DischargeOutcome::DISCHARGED_ALIVE_WITHOUT_SEQUELAE),
            'setdischDx' => new DischargeDiagnosis(DischargeDiagnosis::BACTERIAL_PNEUMONIA),
            'setdischDxOther' => null,
            'setdischClass' => new DischargeClassification(DischargeClassification::CONFIRMED_SPN),
            'setCxrDone' => new TripleChoice(TripleChoice::NO),
        ];
    }

    private function _updateCase($case, $data): void
    {
        foreach ($data as $method => $d) {
            if ($d !== null && method_exists($case, $method)) {
                $case->$method($d);
            }
        }
    }
}
