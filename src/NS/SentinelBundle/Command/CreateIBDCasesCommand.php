<?php

namespace NS\SentinelBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputOption;

use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\IBD\Lab;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Entity\Site;

/**
 * Description of ImportCommand
 *
 * @author gnat
 */
class CreateIBDCasesCommand extends ContainerAwareCommand
{
    private $em;
    
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
        $sites    = $this->em->getRepository('NSSentinelBundle:Site')->getChainByCode($codes);
        $output->writeln("Received ".count($sites)." sites");
        if(count($sites)== 0)
            return;

        $male   = new Gender(Gender::MALE);
        $fmale  = new Gender(Gender::FEMALE);
        $dx[]   = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
        $dx[]   = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
        $dx[]   = new Diagnosis(Diagnosis::SUSPECTED_SEPSIS);
        $dx[]   = new Diagnosis(Diagnosis::OTHER);
        $cxDone = array(
                     new TripleChoice(TripleChoice::YES),
                     new TripleChoice(TripleChoice::NO)
                       );

        for($x = 0; $x < 2700; $x++)
        {
            $dob  = $this->getRandomDate();
            $done = array_rand($cxDone);
            $m = new IBD();

            $m->setDob($dob);
            $m->setAdmDate($this->getRandomDate(null,$dob));
            $m->setCreatedAt($this->getRandomDate(null,$m->getAdmDate()));
            $m->setCsfCollected((($x % 3) == 0));
            $m->setCxrDone($cxDone[$done]);

            if($x%12 == 0)
            {
                $site = new Lab($m);

                $m->setLab($site);

                $this->em->persist($site);
            }

            $m->setGender(($x%7)?$fmale:$male);

            $dxKey   = array_rand($dx);
            $siteKey = array_rand($sites);

            $m->setDischDx($dx[$dxKey]);
            $dxKey   = array_rand($dx);
            $m->setAdmDx($dx[$dxKey]);
            $m->setSite($sites[$siteKey]);
            $m->setCaseId($this->getCaseId($sites[$siteKey]));

            $this->em->persist($m);
            if($x % 100 == 0)
                $this->em->flush();
        }

        $this->em->flush();
        $output->writeln("Create 2700 ibd cases");
    }

    public function getCaseId(Site $site)
    {
        return md5(uniqid().spl_object_hash($site).time());
    }

    public function getRandomDate(\DateTime $before = null, \DateTime $after = null)
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

        return new \DateTime("{$years[$yKey]}-{$months[$mKey]}-{$days[$dKey]}");
    }
}
