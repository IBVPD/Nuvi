<?php

namespace NS\SentinelBundle\Command;

use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Form\Types\BinaxResult;
use \NS\SentinelBundle\Form\Types\CSFAppearance;
use \NS\SentinelBundle\Form\Types\CultureResult;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\LatResult;
use \NS\SentinelBundle\Form\Types\PCRResult;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of ImportCommand
 *
 * @author gnat
 */
class CreateIBDCasesCommand extends BaseCreateCaseCommand
{

    protected function configure()
    {
        $this
            ->setName('nssentinel:ibd:create:cases')
            ->setDescription('Create ibd cases')
            ->addArgument('codes', null, InputArgument::REQUIRED)
            ->addOption('casecount', null, InputOption::VALUE_OPTIONAL, null, 2700)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '768M');
        $codes       = explode(",", str_replace(' ,', ',', $input->getArgument('codes')));
        $caseCount   = $input->getOption('casecount');
        $entityMgr   = $this->getContainer()->get('ns.model_manager');
        $sites       = $entityMgr->getRepository('NSSentinelBundle:Site')->getChainByCode($codes);
        $male        = new Gender(Gender::MALE);
        $fmale       = new Gender(Gender::FEMALE);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_SEPSIS);
        $diagnosis[] = new Diagnosis(Diagnosis::OTHER);
        $cxDone      = array(
            new TripleChoice(TripleChoice::YES),
            new TripleChoice(TripleChoice::NO)
        );

        for ($x = 0; $x < $caseCount; $x++)
        {
            $dob            = $this->getRandomDate();
            $done           = array_rand($cxDone);
            $csfCollected   = (($x % 3) == 0) ? $cxDone[0] : $cxDone[1];
            $bloodCollected = (($x % 2) == 0) ? $cxDone[0] : $cxDone[1];

            $case = new IBD();

            $case->setDob($dob);
            $case->setAdmDate($this->getRandomDate(null, $dob));
            $case->setCreatedAt($this->getRandomDate(null, $case->getAdmDate()));
            $case->setCsfCollected($csfCollected);
            $case->setBloodCollected($bloodCollected);
            $case->setCxrDone($cxDone[$done]);
            $case->setGender(($x % 7) ? $fmale : $male);

            $diagnosisKey = array_rand($diagnosis);
            $siteKey      = array_rand($sites);

            $case->setDischDx($diagnosis[$diagnosisKey]);
            $case->setAdmDx($diagnosis[$diagnosisKey]);
            $case->setSite($sites[$siteKey]);
            $case->setCaseId($this->getCaseId($sites[$siteKey]));

            if ($x % 5 == 0 && $case->getCsfCollected())
                $this->addCsfCollected($case, $cxDone);

            $entityMgr->persist($case);
            if ($x % 100 == 0)
                $entityMgr->flush();
        }

        $entityMgr->flush();
        $output->writeln(sprintf("Create %d ibd cases", $x));
    }

    private function addCsfCollected(IBD $case, $tripleChoice)
    {
        $randArray = rand(1, 100);
        $sLab      = new IBD\SiteLab();
        $sLab->setCsfWcc($randArray);

        if ($case->getBloodCollected() && $case->getBloodCollected()->equal(TripleChoice::YES))
            $sLab->setBloodCultResult(new CultureResult(CultureResult::HI));

        if ($case->getCsfCollected() && $case->getCsfCollected()->equal(TripleChoice::YES))
        {
            if ($randArray < 50)
            {
                $sLab->setCsfCultResult(new CultureResult(CultureResult::NM));
                $sLab->setCsfBinaxDone($tripleChoice[0]);
                if ($randArray < 20)
                    $sLab->setCsfBinaxResult(new BinaxResult(BinaxResult::NEGATIVE));
            }
            else
            {
                if ($randArray < 70)
                {
                    $sLab->setCsfCultResult(new CultureResult(CultureResult::NEGATIVE));
                    $sLab->setCsfBinaxDone($tripleChoice[0]);
                    if ($randArray < 50)
                        $sLab->setCsfBinaxResult(new BinaxResult(BinaxResult::POSITIVE));
                }
                else //PCR+ & Binax- & Culture-
                {
                    $sLab->setCsfCultDone($tripleChoice[0]);
                    $sLab->setCsfCultResult(new CultureResult(CultureResult::NEGATIVE));
                    $sLab->setCsfBinaxDone($tripleChoice[0]);
                    if ($randArray > 90)
                        $sLab->setCsfBinaxResult(new BinaxResult(BinaxResult::NEGATIVE));

                    $sLab->setCsfPcrDone($tripleChoice[0]);
                    $sLab->setCsfPcrResult(new PCRResult(PCRResult::HI));
                }
            }

            if ($randArray > 40 && $randArray < 75)
            {
                $sLab->setCsfLatDone($tripleChoice[0]);
                if ($randArray > 60)
                    $sLab->setCsfLatResult(new LatResult(LatResult::NM));
            }
        }

        if ($randArray < 50)
        {
            $case->setMenFever($tripleChoice[0]);
            $case->setMenAltConscious($tripleChoice[0]);
        }
        else if ($randArray <= 20 || $randArray >= 75)
        {
            $case->setMenFever($tripleChoice[0]);
            $case->setMenNeckStiff($tripleChoice[0]);
        }

        if ($randArray >= 20 && $randArray <= 75)
        {
            $case->setCsfAppearance(new CSFAppearance(CSFAppearance::TURBID));
        }
        else if ($randArray > 40)
        {
            $sLab->setCsfWcc(40);
            $sLab->setCsfGlucose(30);
            $sLab->setCsfProtein(140);
        }

        $case->setSiteLab($sLab);
    }

}
