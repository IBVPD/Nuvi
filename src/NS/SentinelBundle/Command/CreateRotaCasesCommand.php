<?php

namespace NS\SentinelBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputArgument;

use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Entity\RotaVirusSiteLab;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Entity\Site;

/**
 * Description of ImportCommand
 *
 * @author gnat
 */
class CreateRotaCasesCommand extends ContainerAwareCommand
{
    private $em;
    
    protected function configure()
    {
        $this
            ->setName('nssentinel:rota:create:cases')
            ->setDescription('Create RotaVirus cases')
        ; 
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit','768M');

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $sites    = $this->em->getRepository('NSSentinelBundle:Site')->getChainByCode(array('HND129','HND135','BOL78','BOL85','SLV115','SLV112'));
        $male     = new Gender(Gender::MALE);
        $fmale    = new Gender(Gender::FEMALE);
        
        for($x = 0; $x < 2700; $x++)
        {
            $dob     = $this->getRandomDate();
            $siteKey = array_rand($sites);

            $m = new RotaVirus();
            $m->setDob($dob);
            $m->setAdmissionDate($this->getRandomDate(null,$dob));
            $m->setSite($sites[$siteKey]);
            $m->setCaseId($this->getCaseId($sites[$siteKey]));

            $m->setGender(($x%7)?$fmale:$male);

            if($x%12 == 0)
            {
                $lab = new RotaVirusSiteLab($m);

                $m->setLab($lab);

                $this->em->persist($lab);
            }

            $this->em->persist($m);
            if($x % 100 == 0)
                $this->em->flush();
        }

        $this->em->flush();
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
