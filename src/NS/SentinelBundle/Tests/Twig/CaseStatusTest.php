<?php

namespace NS\SentinelBundle\Tests\Twig;

use NS\SentinelBundle\Twig\CaseStatus;
use NS\SentinelBundle\Entity\Meningitis;
use NS\SentinelBundle\Form\Types\CaseStatus as FormCaseStatus;

/**
 * Description of CaseStatusTest
 *
 * @author gnat
 */
class CaseStatusTest extends \PHPUnit_Framework_TestCase
{
    public function testIncompleteCase()
    {
        $case   = new Meningitis();
        $status = new CaseStatus();
        $label  = $status->getLabel($case, 'nothing');

        $this->assertContains('label-warning',$label,"Incomplete case has warning label");
    }

    public function testCompleteCase()
    {
        $case   = new Meningitis();
        $case->setStatus(new FormCaseStatus(FormCaseStatus::COMPLETE));
        $status = new CaseStatus();
        $label  = $status->getLabel($case, 'nothing');

        $this->assertContains('label-success',$label,"Complete case has success label");
    }
//
//    public function testErrorCase()
//    {
//        $status = new CaseStatus();
//
//        $case   = new Meningitis();
//        $lab = new \NS\SentinelBundle\Entity\IBD\Lab();
//        $lab->setCsfSentToNL(1);
////        $lab->setSentToNationalLab(true);
//        $case->setLab($lab);
//
//        $label  = $status->getLabel($case, 'nothing');
//
//        $this->assertContains('label-danger',$label,"Case with data sent to national lab but without a national lab has danger label");
//
//        $case   = new Meningitis();
//        $lab = new \NS\SentinelBundle\Entity\IBD\Lab();
////        $lab->setSentToReferenceLab(true);
//        $case->setLab($lab);
//
//        $label  = $status->getLabel($case, 'nothing');
//
//        $this->assertContains('label-danger',$label,"Case with data sent to reference lab but without a reference lab has danger label");
//    }

    public function testLabIncomplete()
    {
        $status = new CaseStatus();

        $case   = new Meningitis();
        $lab    = new \NS\SentinelBundle\Entity\IBD\Lab();
        $case->setLab($lab);

        $label  = $status->getLabLabel($case, 'nothing');

        $this->assertContains('label-warning',$label,"Incomplete lab has warning label");
    }

    public function testLabComplete()
    {
        $status = new CaseStatus();

        $case   = new Meningitis();
        $lab    = new \NS\SentinelBundle\Entity\IBD\Lab();
        $lab->setStatus(new FormCaseStatus(FormCaseStatus::COMPLETE));
        $case->setLab($lab);

        $label  = $status->getLabLabel($case, 'nothing');

        $this->assertContains('label-success',$label,"Complete case has success label");
    }

    public function testLabErrorCase()
    {
        $status = new CaseStatus();

        $case   = new Meningitis();

        $label  = $status->getLabLabel($case, 'nothing');

        $this->assertContains('label-danger', $label, "Case without lab record has danger label");
    }
}
