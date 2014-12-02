<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Doctrine\Common\Persistence\ObjectManager;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Entity\Site;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;

class LoadIBDCaseData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $male  = new Gender(Gender::MALE);
        $fmale = new Gender(Gender::FEMALE);
        $dx[]  = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
        $dx[]  = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
        $dx[]  = new Diagnosis(Diagnosis::SUSPECTED_SEPSIS);
        $dx[]  = new Diagnosis(Diagnosis::OTHER);

        $a     = $this->getReference('site-alberta');
        $s     = $this->getReference('site-seattle');
        $t     = $this->getReference('site-toronto');
        $mx    = $this->getReference('site-mexico');
        $sites = array(3=>$a,5=>$s,7=>$t,8=>$mx);

        $before = new \DateTime("2015-01-01");
        $after  = new \DateTime("2014-01-01");
        foreach($sites as $num => $site)
        {
            for($x = 0; $x < $num; $x++)
            {
                $dob = $this->getRandomDate(null, $after);
                $m = new IBD();
                $m->setDob($dob);
                $m->setAdmDate($this->getRandomDate($before, $after));
                if ($x % 3)
                    $m->setCsfCollected($done);
                else
                    $m->setCsfCollected($nDone);

                $m->setGender(($x%4)?$fmale:$male);
                $dxKey = array_rand($dx);
                $m->setDischDx($dx[$dxKey]);

                $m->setCaseId($this->getCaseId($site,$x));
                $m->setSite($site);

                $manager->persist($m);
            }
        }

        $manager->flush();
    }

    private function getCaseId(Site $site, $x)
    {
        return $site->getCode().'-'.$x;
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
