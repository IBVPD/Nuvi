<?php

namespace NS\SentinelBundle\Command;

use DateTime;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\Types\CSFAppearance;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\TripleChoice;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of ImportCommand
 *
 * @author gnat
 */
class CreateIBDCasesCommand extends ContainerAwareCommand
{
    private $entityMgr;
    
    protected function configure()
    {
        $this
            ->setName('nssentinel:ibd:create:cases')
            ->setDescription('Create ibd cases')
            ->addOption('codes',null, InputOption::VALUE_OPTIONAL, null, 'NIC-53,NIC-56,NIC-57,NIC-61,NIC-65,SLV-114,SLV-116,SLV-26,SLV-27,SLV-28,SLV-33,SLV-34,SLV-36,SLV-5,BGD-1,BGD-2,BGD-3')
        ; 
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit','768M');
        $codes = explode(",", str_replace(' ,',',',$input->getOption('codes')));
        if(empty($codes))
        {
            $output->writeln("No codes to add cases to");
            return;
        }

        $this->em = $this->getContainer()->get('ns.model_manager');
        $sites    = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getChainByCode($codes);
        $output->writeln("Received ".count($sites)." sites");
        if(count($sites)== 0)
            return;

        $male        = new Gender(Gender::MALE);
        $fmale       = new Gender(Gender::FEMALE);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_SEPSIS);
        $diagnosis[] = new Diagnosis(Diagnosis::OTHER);
        $cxDone = array(
                     new TripleChoice(TripleChoice::YES),
                     new TripleChoice(TripleChoice::NO)
                       );

        for($x = 0; $x < 2700; $x++)
        {
            $dob   = $this->getRandomDate();
            $done  = array_rand($cxDone);

            $case = new IBD();

            $case->setDob($dob);
            $case->setAdmDate($this->getRandomDate(null,$dob));
            $case->setCreatedAt($this->getRandomDate(null,$case->getAdmDate()));
            $case->setCsfCollected((($x % 3) == 0));
            $case->setCxrDone($cxDone[$done]);
            $case->setGender(($x%7)?$fmale:$male);

            $diagnosisKey   = array_rand($diagnosis);
            $siteKey = array_rand($sites);

            $case->setDischDx($diagnosis[$diagnosisKey]);
            $case->setAdmDx($diagnosis[$diagnosisKey]);
            $case->setSite($sites[$siteKey]);
            $case->setCaseId($this->getCaseId($sites[$siteKey]));

            if($x%5 == 0 && $case->getCsfCollected())
                $this->addCsfCollected($case,$cxDone[0]);

            $this->entityMgr->persist($case);
            if($x % 100 == 0)
                $this->entityMgr->flush();
        }

        $this->entityMgr->flush();
        $output->writeln("Create 2700 ibd cases");
    }

    public function getCaseId(Site $site)
    {
        return md5(uniqid().spl_object_hash($site).time());
    }

    public function getRandomDate(DateTime $before = null, DateTime $after = null)
    {
        $years  = range(1995,date('Y'));
        $months = range(1,12);
        $days   = range(1,28);

        $yKey   = array_rand($years);
        $mKey   = array_rand($months);
        $dKey   = array_rand($days);

        if($before != null)
        {
            $byear = $before->format('Y');
            while($years[$yKey] > $byear)
                $yKey = array_rand($years);
        }

        if($after != null)
        {
            $ayear = $after->format('Y');
            while($years[$yKey] < $ayear)
            {
                $yKey = array_rand($years);
            }
        }

        return new DateTime("{$years[$yKey]}-{$months[$mKey]}-{$days[$dKey]}");
    }

    private function addCsfCollected($case,$cxDone)
    {
        $randArray = rand(1,100);
        $case->setCsfWcc($randArray);

        if($randArray<50)
        {
            $case->setMenFever($cxDone[0]);
            $case->setMenAltConscious($cxDone[0]);
        }
        else if($randArray <= 20 || $randArray >= 75 )
        {
            $case->setMenFever($cxDone[0]);
            $case->setMenNeckStiff($cxDone[0]);
        }

        if($randArray >=20 && $randArray <= 75)
        {
            $case->setCsfAppearance(new CSFAppearance(CSFAppearance::TURBID));
        }
        else if($randArray > 40)
        {
            $case->setCsfWcc(40);
            $case->setCsfGlucose(30);
            $case->setCsfProtein(140);
        }
    }
}
