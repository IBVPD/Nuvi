<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \NS\SentinelBundle\Entity\RotaVirus;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Entity\Site;

class LoadRotaVirusCaseData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    private $container;

    public function getOrder()
    {
        return 4;
    }

    public function load(ObjectManager $manager)
    {
        ini_set('memory_limit', '768M');

        $done  = new TripleChoice(TripleChoice::YES);
        $nDone = new TripleChoice(TripleChoice::NO);

        $male        = new Gender(Gender::MALE);
        $fmale       = new Gender(Gender::FEMALE);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_SEPSIS);
        $diagnosis[] = new Diagnosis(Diagnosis::OTHER);

        $salberta = $this->getReference('site-alberta');
        $sseattle = $this->getReference('site-seattle');
        $storonto = $this->getReference('site-toronto');
        $smexico  = $this->getReference('site-mexico');
        $sites    = array(8 => $salberta, 7 => $sseattle, 4 => $storonto, 2 => $smexico);
        $before   = new \DateTime("2015-01-01");
        $after    = new \DateTime("2014-01-01");

        foreach ($sites as $num => $site)
        {
            for ($x = 0; $x < $num; $x++)
            {
                $dob = $this->getRandomDate(null, $after);
                $m   = new RotaVirus();
                $m->setDob($dob);
                $m->setAdmDate($this->getRandomDate($before, $dob));
                $m->setGender(($x % 3) ? $fmale : $male);
                $m->setCaseId($this->getCaseId($site, $x));
                $m->setSite($site);

                $manager->persist($m);
            }
        }

        $manager->flush();
    }

    private function getCaseId(Site $site)
    {
        return md5(uniqid() . spl_object_hash($site) . time());
    }

    public function getRandomDate(\DateTime $before = null, \DateTime $after = null)
    {
        $years  = range(1995, date('Y'));
        $months = range(1, 12);
        $days   = range(1, 28);

        $yKey = array_rand($years);
        $mKey = array_rand($months);
        $dKey = array_rand($days);

        if ($before != null)
        {
            $byear = $before->format('Y');
            while ($years[$yKey] > $byear)
                $yKey  = array_rand($years);
        }

        if ($after != null)
        {
            $ayear = $after->format('Y');
            while ($years[$yKey] < $ayear)
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
