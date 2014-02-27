<?php

namespace NS\SentinelBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputArgument;

use NS\SentinelBundle\Entity\Meningitis;
use \NS\SentinelBundle\Entity\SiteLab;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\Diagnosis;

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
        ; 
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit','768M');

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $sites = $this->em->getRepository('NSSentinelBundle:Site')->getChainByCode(array('HND129','HND135','BOL78','BOL85','SLV115','SLV112'));

        $cxDone = array(
                     new TripleChoice(TripleChoice::YES),
                     new TripleChoice(TripleChoice::NO)
                       );

        $male  = new Gender(Gender::MALE);
        $fmale = new Gender(Gender::FEMALE);
        
        $dx[]   = new Diagnosis(Diagnosis::MENINGITIS);
        $dx[]   = new Diagnosis(Diagnosis::PNEUMONIA);
        $dx[]   = new Diagnosis(Diagnosis::SEPSIS);
        $dx[]   = new Diagnosis(Diagnosis::OTHER);
    
        for($x = 0; $x < 2700; $x++)
        {
            $dob = $this->getRandomDate();
            $m = new Meningitis();
            $m->setDob($dob);
            $m->setAdmDate($this->getRandomDate(null,$dob));
            $m->setCsfCollected((($x % 3) == 0));
            if($x%12 == 0)
            {
                $site = new SiteLab($m);

                $done = array_rand($cxDone);
                $site->setCxrDone($cxDone[$done]);
                $m->setSiteLab($site);

                $this->em->persist($site);
            }

            $m->setGender(($x%7)?$fmale:$male);

            $dxKey   = array_rand($dx);
            $siteKey = array_rand($sites);

            $m->setDischDx($dx[$dxKey]);
            $m->setSite($sites[$siteKey]);

            $this->em->persist($m);
            if($x % 100 == 0)
                $this->em->flush();
        }

        $this->em->flush();
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
