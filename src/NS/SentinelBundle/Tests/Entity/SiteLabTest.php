<?php

namespace NS\SentinelBundle\Tests\Entity;

use \NS\SentinelBundle\Entity\IBD\SiteLab;
use \NS\SentinelBundle\Form\Types\CultureResult;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of SiteLabTest
 *
 * @author gnat
 */
class SiteLabTest extends WebTestCase
{

    public function testSiteLabValidation()
    {

        $kernel    = $this->getKernel();
        $validator = $kernel->getContainer()->get('validator');

        $lab = new SiteLab();
        $lab->setCsfCultDone(new TripleChoice(TripleChoice::YES));

        $violationList = $validator->validate($lab);

        $this->assertEquals(1, $violationList->count());
        $this->assertEquals('The other fields should have content.', $violationList[0]->getMessage());
        $this->assertEquals('csfCultDone', $violationList[0]->getPropertyPath());

        $lab->setCsfCultResult(new CultureResult(CultureResult::NEGATIVE));

        $violationList = $validator->validate($lab);
        $this->assertEquals(0, $violationList->count());
    }

    public function testSiteLabValidation2()
    {
        $lab = new SiteLab();
        $lab->setCsfCultDone(new TripleChoice(TripleChoice::YES));
        $lab->setCsfCultResult(new CultureResult(CultureResult::OTHER));

        $kernel        = $this->getKernel();
        $validator     = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($lab);

        $this->assertEquals(1, $violationList->count());
        $this->assertEquals('The other fields should have content.', $violationList[0]->getMessage());
        $this->assertEquals('csfCultResult', $violationList[0]->getPropertyPath());
    }

    private function getKernel()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        return $kernel;
    }
}