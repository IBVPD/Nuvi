<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \NS\SentinelBundle\Entity\Meningitis;
use \NS\SentinelBundle\Form\Types\TripleChoice;

class LoadMeningitisCaseData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function getOrder()
    {
        return 3;
    }

    public function load(ObjectManager $manager)
    {
        $done  = new TripleChoice(TripleChoice::YES);
        $nDone = new TripleChoice(TripleChoice::NO);
        $today = new \DateTime();
        
        for($x = 0; $x<1000; $x++)
        {
            $m = new Meningitis();
            $m->setDob($today);
//            $m->setAdmDate($today);
            $m->setCsfCollected((($x % 3) == 0));
//            $m->setCxrDone(($x%5) == 0 ? $done:$nDone);
            $m->setSite($this->getReference('site-alberta'));
            
            $manager->persist($m);
        }
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
