<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\IBD\SiteLab;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteLabTest extends WebTestCase
{

    /**
     * @dataProvider getIncompleteSiteLabs
     *
     * @param $failSite
     * @param $failures
     * @param $message
     * @param $path
     * @param $passSite
     */
    public function testSiteLabValidation($failSite, $failures, $message, $path, $passSite): void
    {
        $kernel         = $this->getKernel();
        $validator      = $kernel->getContainer()->get('validator');
        $violationList1 = $validator->validate($failSite, null, ['Default']);

        $this->assertEquals($failures, $violationList1->count());
        $this->assertEquals($message, $violationList1[0]->getMessage());
        $this->assertEquals($path, $violationList1[0]->getPropertyPath(), print_r($violationList1, true));

        $violationList2 = $validator->validate($passSite);
        $this->assertEquals(0, $violationList2->count());
    }

    public function getSiteLab(): SiteLab
    {
        return new SiteLab();
    }

    public function getIncompleteSiteLabs(): array
    {
        return [
            [
                'failSite' => $this->getSiteLab()->setCsfCultDone(new TripleChoice(TripleChoice::YES)),
                'failures' => 1,
                'message' => 'This value should not be blank',
                'path' => 'csfCultResult',
                'passSite' => $this->getSiteLab()->setCsfCultDone(new TripleChoice(TripleChoice::YES))->setCsfCultResult(new CultureResult(CultureResult::NEGATIVE)),
            ],
//            [
//                'failSite' => $this->getSiteLab()->setCsfCultDone(new TripleChoice(TripleChoice::YES))->setCsfCultResult(new CultureResult(CultureResult::OTHER)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-csfCult-was-done-without-result-other',
//                'path'     => 'csfCultResult',
//                'passSite' => $this->getSiteLab()->setCsfCultDone(new TripleChoice(TripleChoice::YES))->setCsfCultResult(new CultureResult(CultureResult::OTHER))->setCsfCultOther('Other'),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setBloodCultDone(new TripleChoice(TripleChoice::YES)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-bloodCult-was-done-without-result',
//                'path'     => 'bloodCultDone',
//                'passSite' => $this->getSiteLab()->setBloodCultDone(new TripleChoice(TripleChoice::YES))->setBloodCultResult(new CultureResult(CultureResult::NEGATIVE)),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setBloodCultDone(new TripleChoice(TripleChoice::YES))->setBloodCultResult(new CultureResult(CultureResult::OTHER)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-bloodCult-was-done-without-result-other',
//                'path'     => 'bloodCultResult',
//                'passSite' => $this->getSiteLab()->setBloodCultDone(new TripleChoice(TripleChoice::YES))->setBloodCultResult(new CultureResult(CultureResult::OTHER))->setBloodCultOther('Other'),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setOtherCultDone(new TripleChoice(TripleChoice::YES)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-otherCult-was-done-without-result',
//                'path'     => 'otherCultDone',
//                'passSite' => $this->getSiteLab()->setOtherCultDone(new TripleChoice(TripleChoice::YES))->setOtherCultResult(new CultureResult(CultureResult::NEGATIVE)),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setOtherCultDone(new TripleChoice(TripleChoice::YES))->setOtherCultResult(new CultureResult(CultureResult::OTHER)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-otherCult-was-done-without-result-other',
//                'path'     => 'otherCultResult',
//                'passSite' => $this->getSiteLab()->setOtherCultDone(new TripleChoice(TripleChoice::YES))->setOtherCultResult(new CultureResult(CultureResult::OTHER))->setOtherCultOther('Other'),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setCsfLatDone(new TripleChoice(TripleChoice::YES)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-csfLat-was-done-without-result',
//                'path'     => 'csfLatDone',
//                'passSite' => $this->getSiteLab()->setCsfLatDone(new TripleChoice(TripleChoice::YES))->setCsfLatResult(new LatResult(LatResult::NEGATIVE)),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setCsfLatDone(new TripleChoice(TripleChoice::YES))->setCsfLatResult(new LatResult(LatResult::OTHER)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-csfLat-was-done-without-result-other',
//                'path'     => 'csfLatResult',
//                'passSite' => $this->getSiteLab()->setCsfLatDone(new TripleChoice(TripleChoice::YES))->setCsfLatResult(new LatResult(LatResult::OTHER))->setCsfLatOther('Other'),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setCsfPcrDone(new TripleChoice(TripleChoice::YES)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-csfPcr-was-done-without-result',
//                'path'     => 'csfPcrDone',
//                'passSite' => $this->getSiteLab()->setCsfPcrDone(new TripleChoice(TripleChoice::YES))->setCsfPCRResult(new PCRResult(PCRResult::NEGATIVE)),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setCsfPcrDone(new TripleChoice(TripleChoice::YES))->setCsfPCRResult(new PCRResult(PCRResult::OTHER)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-csfPcr-was-done-without-result-other',
//                'path'     => 'csfPcrResult',
//                'passSite' => $this->getSiteLab()->setCsfPcrDone(new TripleChoice(TripleChoice::YES))->setCsfPCRResult(new PCRResult(PCRResult::OTHER))->setCsfPcrOther('Other'),
//            ],
//            [
//                'failSite' => $this->getSiteLab()->setCsfBinaxDone(new TripleChoice(TripleChoice::YES)),
//                'failures' => 1,
//                'message'  => 'form.validation.ibd-sitelab-csfBinax-was-done-without-result',
//                'path'     => 'csfBinaxDone',
//                'passSite' => $this->getSiteLab()->setCsfBinaxDone(new TripleChoice(TripleChoice::YES))->setCsfBinaxResult(new BinaxResult(BinaxResult::INCONCLUSIVE)),
//            ],
        ];
    }

    private function getKernel()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        return $kernel;
    }
}
