<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \NS\SentinelBundle\Entity\Meningitis;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\Diagnosis;

class LoadMeningitisCaseData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function getOrder()
    {
        return 3;
    }

    public function load(ObjectManager $manager)
    {
        ini_set('memory_limit','768M');

        $done  = new TripleChoice(TripleChoice::YES);
        $nDone = new TripleChoice(TripleChoice::NO);
        
        $a     = $this->getReference('site-alberta');
        $s     = $this->getReference('site-seattle');
        $t     = $this->getReference('site-toronto');
        $mx    = $this->getReference('site-mexico');
        $male  = new Gender(Gender::MALE);
        $fmale = new Gender(Gender::FEMALE);
        $dx[]  = new Diagnosis(Diagnosis::MENINGITIS);
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
            $m->setCxrDone(($x%5) == 0 ? $done:$nDone);

            $m->setGender(($x%7)?$fmale:$male);

            $dxKey = array_rand($dx);
            $m->setDischDx($dx[$dxKey]);

            if(($x % 3) == 0 )
                $m->setSite($a);
            else if(($x % 5) == 0 )
                $m->setSite($s);
            else if(($x % 11) == 0)
                $m->setSite($t);
            else 
                $m->setSite($mx);

            $manager->persist($m);
        }

        $manager->flush();
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

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
