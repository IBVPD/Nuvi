<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 2017-03-07
 * Time: 1:07 PM
 */

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Entity\ValueObjects\YearMonth;
use NS\SentinelBundle\Form\Types\TripleChoice;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RotaVirusTest extends KernelTestCase
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var \DateTime */
    private $today;

    /** @var \DateTime */
    private $past;

    /** @var \DateTime */
    private $future;

    /** @var  RotaVirus */
    private $rotaVirusCase;

    /** @var TripleChoice */
    private $tripleYes;

    /** @var TripleChoice */
    private $tripleNo;

    public function setUp()
    {
        self::bootKernel();

        $this->validator = static::$kernel->getContainer()->get('validator');
        $this->today     = new \DateTime();
        $this->past      = new \DateTime('-10 days');
        $this->future    = new \DateTime('-5 days');
        $this->tripleYes = new TripleChoice(TripleChoice::YES);
        $this->tripleNo  = new TripleChoice(TripleChoice::NO);

        $this->rotaVirusCase = new RotaVirus();
        $this->rotaVirusCase->setCaseId('caseId-1');
        $this->rotaVirusCase->setBirthdate($this->past);
        $this->rotaVirusCase->setStoolCollected($this->tripleNo);
    }

    public function testBirthdayOrYearMonth()
    {
        $violations = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(0, $violations);
        $this->rotaVirusCase->setAdmDate(null);
        $this->rotaVirusCase->setDobKnown($this->tripleNo);
        $this->rotaVirusCase->setDobYearMonths(new YearMonth(2, 1));
        $violations = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(0, $violations);
    }

    public function testRequiredFields()
    {
        $rv = new RotaVirus();
        /** @var ConstraintViolationList $violations */
        $violations = $this->validator->validate($rv);
        $this->assertCount(3, $violations);

        $this->assertEquals('The birthdate or age is required', $violations[0]->getMessage());
        $this->assertEquals('dobKnown', $violations[0]->getPropertyPath());
        $this->assertEquals('This value should not be blank.', $violations[1]->getMessage());
        $this->assertEquals('stool_collected', $violations[1]->getPropertyPath());
        $this->assertEquals('This value should not be blank.', $violations[2]->getMessage());
        $this->assertEquals('case_id', $violations[2]->getPropertyPath());
    }

    public function testVaccinationBeforeAdmission()
    {
        $this->rotaVirusCase->setAdmDate($this->past);
        $this->rotaVirusCase->setFirstVaccinationDose($this->future);

        /** @var ConstraintViolationList[] $violationList */
        $violationList = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(1, $violationList);
        $this->assertEquals('firstVaccinationDose', $violationList[0]->getConstraint()->lessThanField);

        $this->rotaVirusCase->setFirstVaccinationDose($this->past);
        $this->rotaVirusCase->setSecondVaccinationDose($this->future);

        $violationList = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(1, $violationList);
        $this->assertEquals('secondVaccinationDose', $violationList[0]->getConstraint()->lessThanField);

        $this->rotaVirusCase->setFirstVaccinationDose($this->past);
        $this->rotaVirusCase->setSecondVaccinationDose($this->past);
        $this->rotaVirusCase->setThirdVaccinationDose($this->future);

        $violationList = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(1, $violationList);
        $this->assertEquals('thirdVaccinationDose', $violationList[0]->getConstraint()->lessThanField);
    }

    public function testStoolCollectionDateAfterAdmission()
    {
        $this->rotaVirusCase->setAdmDate($this->future);
        $this->rotaVirusCase->setStoolCollectionDate($this->past);
        $violationList = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(1, $violationList);
        $this->assertEquals('admDate', $violationList[0]->getConstraint()->lessThanField);

        $this->rotaVirusCase->setAdmDate($this->past);
        $this->rotaVirusCase->setStoolCollectionDate($this->future);
        $violationList = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(0, $violationList);
    }

    public function testDischargeDateAfterAdmission()
    {
        $this->rotaVirusCase->setAdmDate($this->future);
        $this->rotaVirusCase->setDischargeDate($this->past);
        $violationList = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(1, $violationList);
        $this->assertEquals('admDate', $violationList[0]->getConstraint()->lessThanField);

        $this->rotaVirusCase->setAdmDate($this->past);
        $this->rotaVirusCase->setDischargeDate($this->future);
        $violationList = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(0, $violationList);
    }

    public function testStoolCollectionDateRequiredWhenStoolIsCollected()
    {
        $this->rotaVirusCase->setStoolCollected($this->tripleYes);
        $violationList = $this->validator->validate($this->rotaVirusCase);
        $this->assertCount(1, $violationList);
        $this->assertEquals("Due to response for 'stoolCollected' field, related field 'stoolCollectionDate' is required", $violationList[0]->getMessage());
    }

// date of collection, date of local lab reception
    public function testSiteLabReceivedBeforeCollected()
    {
        $this->rotaVirusCase->setStoolCollected($this->tripleYes);
        $this->rotaVirusCase->setStoolCollectionDate($this->future);
        $no      = new TripleChoice(TripleChoice::NO);
        $siteLab = new RotaVirus\SiteLab();
        $siteLab->setCaseFile($this->rotaVirusCase);
        $siteLab->setReceived($this->past);
        //Added as minimum required fields, but likely only for PAHO/AMR
        $siteLab->setElisaDone($no);
        $siteLab->setStored($no);
        $siteLab->setAdequate($no);
        $violationConstraints = $this->validator->validate($siteLab);
        $this->assertCount(1, $violationConstraints);
        $this->assertEquals('caseFile.stoolCollectionDate', $violationConstraints[0]->getConstraint()->lessThanField);
    }

    // date of sent to national lab, date of reception to the national lab
    public function testNationalLabReceivedBeforeSent()
    {
        $siteLab = new RotaVirus\SiteLab();
        $siteLab->setCaseFile($this->rotaVirusCase);
        $siteLab->setStoolSentToNLDate($this->future);

        $nationalLab = new RotaVirus\NationalLab();
        $nationalLab->setLabId('labId');
        $nationalLab->setDateReceived($this->past);

        $this->rotaVirusCase->setSiteLab($siteLab);
        $nationalLab->setCaseFile($this->rotaVirusCase);

        $violationConstraints = $this->validator->validate($nationalLab);
        // 3 because of the diarrhea
        $this->assertCount(3, $violationConstraints);
        $this->assertEquals('caseFile.siteLab.stoolSentToNLDate', $violationConstraints[0]->getConstraint()->lessThanField);
    }

    //date of results at the national lab
    public function testNationalLabResultsBeforeReceived()
    {
        $nationalLab = new RotaVirus\NationalLab();
        $nationalLab->setLabId('labId');
        $nationalLab->setDateReceived($this->future);
        $nationalLab->setGenotypingDate($this->past);

        $violationConstraints = $this->validator->validate($nationalLab);
        // 3 because of the diarrhea
        $this->assertCount(3, $violationConstraints);
        $this->assertEquals('dateReceived', $violationConstraints[0]->getConstraint()->lessThanField);
    }
}
