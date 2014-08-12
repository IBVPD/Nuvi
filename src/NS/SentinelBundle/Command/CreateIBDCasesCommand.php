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
            ->addOption('codes',null, InputOption::VALUE_OPTIONAL, null, 'HND129,HND135,BOL78,BOL85,SLV115,SLV112')
        ; 
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit','768M');
        $codes    = explode(",", str_replace(' ','',$input->getOption('codes')));
        if(empty($codes))
        {
            $output->writeln("No codes to add cases to");
            return;
        }

        $this->em = $this->getContainer()->get('ns.model_manager');
        $sites    = $this->em->getRepository('NSSentinelBundle:Site')->getChainByCode($codes);

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
