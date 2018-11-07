<?php

namespace NS\SentinelBundle\Tests\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Listener\PneumoniaListener;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
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

class PneumoniaListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testMinimumRequiredFieldsNonPaho()
    {
        $country = new Country('tId', 'Test');
        $country->setTracksPneumonia(true);
        $region = new Region('RCODE', 'Region');
        $country->setRegion($region);

        $case = new Pneumonia();
        $case->setCountry($country);

        $listener = new PneumoniaListener();

        $this->assertCount(26, $listener->getMinimumRequiredFields($case));
        $this->assertCount(29, $listener->getMinimumRequiredFields($case, 'AMR'));
    }

    //============================================================
    // Single complete cases
    public function testSingleMinimumCompleteCaseNonPaho()
    {
        $case = new Pneumonia();
        $this->_updateCase($case, $this->getSingleCompleteCaseNonPaho());
        $listener = new PneumoniaListener();
        $listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertTrue($case->isComplete(), "New cases are incomplete " . $listener->getIncompleteField($case) . ' ' . $case->getStatus());
    }

    //============================================================
    // Incomplete
    /**
     * @depends      testSingleMinimumCompleteCaseNonPaho
     * @dataProvider getIncompleteTestDataNonPaho
     * @param $data
     */
    public function testCaseIsIncompleteNonPaho($data)
    {
        $case = new Pneumonia();
        $this->_updateCase($case, $data);
        $listener = new PneumoniaListener();
        $listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertFalse($case->isComplete(), "New cases are incomplete data removed '" . (isset($data['removed']) ? $data['removed'] : 'no removed') . "'");
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::OPEN);
    }

    //============================================================
    // Complete
    /**
     * @depends      testSingleMinimumCompleteCaseNonPaho
     * @dataProvider getCompleteCaseNonPahoData
     * @param $data
     */
    public function testCaseIsCompleteWithPneuomia($data)
    {
        $case = new Pneumonia();
        $this->_updateCase($case, $data);
        $listener = new PneumoniaListener();
        $listener->prePersist($case, $this->createMock(LifecycleEventArgs::class));

        $this->assertTrue($case->isComplete(), "Cases with pneunomia are complete " . (!$case->isComplete()) ? $listener->getIncompleteField($case) : null);
        $this->assertEquals($case->getStatus()->getValue(), CaseStatus::COMPLETE);
    }

    //=============================================================
    // data providers

    public function getCompleteCaseNonPahoData()
    {
        $data = [];
        $complete = $this->getSingleCompleteCaseNonPaho();
        $country = new Country('tId', 'TestCountry');
        $country->setTracksPneumonia(true);
        $region = new Region('RCODE', 'Region');
        $country->setRegion($region);
        $case = new Pneumonia();
        $case->setCountry($country);

        $data[] = ['data' => $complete];

        return $this->_commonCompleteData($data, $complete);
    }

    private function _commonCompleteData($data, $complete)
    {
        $tripleYes = new TripleChoice(TripleChoice::YES);

        //admDx + admDxOther
        $row = $complete;
        $row['setadmDx'] = new Diagnosis(Diagnosis::OTHER);
        $row['setadmDxOther'] = 'null';
        $data[] = ['data' => $row];

        //dischDx + dischDxOther
        $row = $complete;
        $row['setdischDx'] = new DischargeDiagnosis(DischargeDiagnosis::OTHER);
        $row['setdischDxOther'] = 'null';
        $data[] = ['data' => $row];

        //meningReceived + meningDoses
        $row = $complete;
        $row['setmeningReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $row['setmeningType'] = new VaccinationType(VaccinationType::ACW135);
        $row['setmeningDate'] = new \DateTime();
        $data[] = ['data' => $row];

        $doses = new ThreeDoses();
        foreach ($doses->getValues() as $x => $v) {
            //hibReceived + hibDoses
            $row = $complete;
            $row['sethibReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
            $row['sethibDoses'] = new FourDoses($x);
            $data[] = ['data' => $row];

            //pcvReceived + pcvDoses
            $row = $complete;
            $row['setpcvReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
            $row['setpcvDoses'] = new FourDoses($x);
            $data[] = ['data' => $row];
        }

        //csfCollected + related
        $csfAppearance = new CSFAppearance();
        foreach ($csfAppearance->getValues() as $v) {
            $row = $complete;
            $row['setcsfCollected'] = $tripleYes;
            $row['setcsfId'] = 'null';
            $row['setcsfCollectDate'] = new \DateTime();
            $row['setcsfCollectTime'] = $row['setcsfCollectDate'];
            $row['setcsfAppearance'] = new CSFAppearance($v);
            $data[] = ['data' => $row];
        }

        return $data;
    }

    public function getIncompleteTestDataNonPaho()
    {
        $data = [];
        $complete = $this->getSingleCompleteCaseNonPaho();
        $fields = [
            'caseId',
            'birthdate',
            'gender',
            'district',
            'admDate',
            'onsetDate',
//            'admDx', this is set by default in the Pneumonia constructor now so can't ever be empty
            'antibiotics',
            'hibReceived',
            'pcvReceived',
            'meningReceived',
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
            'pneuMalnutrition',
        ];

        foreach ($fields as $field) {
            if (isset($complete["set$field"])) {
                $d = $complete;
                unset($d["set$field"]);

                $d['removed'] = $field;
                $data[] = ['data' => $d];
            }
        }

        //admDx + admDxOther
        $d = $complete;
        $d['setadmDx'] = new Diagnosis(Diagnosis::OTHER);
        $d['setadmDxOther'] = null;
        $data[] = ['data' => $d];

        //dischDx + dischDxOther
        $d = $complete;
        $d['setdischDx'] = new DischargeDiagnosis(DischargeDiagnosis::OTHER);
        $d['setdischDxOther'] = null;
        $data[] = ['data' => $d];

        //hibReceived + hibDoses
        $d = $complete;
        $d['sethibReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $d['sethibDoses'] = null;
        $data[] = ['data' => $d];

        //pcvReceived + pcvDoses
        $d = $complete;
        $d['setpcvReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $d['setpcvDoses'] = null;
        $data[] = ['data' => $d];

        //meningReceived + meningDoses
        $d = $complete;
        $d['setmeningReceived'] = new VaccinationReceived(VaccinationReceived::YES_CARD);
        $d['setmeningType'] = null;
        $data[] = ['data' => $d];

        return $data;
    }

    private function getSingleCompleteCaseNonPaho()
    {
        $tripleNo = new TripleChoice(TripleChoice::NO);
        $country = new Country('tId', 'Test Country');
        $country->setTracksPneumonia(true);
        $region = new Region('RCODE', 'Region');
        $country->setRegion($region);


        return [
            'setcountry' => $country,
            'setcaseId' => 'blah',
            'setbirthdate' => new \DateTime(),
            'setgender' => new Gender(Gender::MALE),
            'setdistrict' => 'The District',
            'setadmDate' => new \DateTime(),
            'setonsetDate' => new \DateTime(),
            'setadmDx' => new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            'setadmDxOther' => null,
            'setantibiotics' => $tripleNo,
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

    private function _updateCase($case, $data)
    {
        foreach ($data as $method => $d) {
            if (!is_null($d) && method_exists($case, $method)) {
                $case->$method($d);
            }
        }
    }
}
