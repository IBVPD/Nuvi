<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;

class LoadRegionData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $r = new Region();
        $r->setName('North America');
        $r->setCode('NA');
        
        $r2 = new Region();
        $r2->setName('South East Asia');
        $r2->setCode('SEA');

        $c = new Country();
        $c->setCode('CA');
        $c->setName('Canada');
        $c->setIsActive(true);
        $c->setRegion($r);
        $c->setHasReferenceLab(true);
        $c->setHasNationalLab(true);

        $c2 = new Country();
        $c2->setCode('US');
        $c2->setName('United States');
        $c2->setIsActive(true);
        $c2->setRegion($r);
        $c2->setHasReferenceLab(true);
        $c2->setHasNationalLab(true);

        $c3 = new Country();
        $c3->setCode('MX');
        $c3->setName('Mexico');
        $c3->setIsActive(true);
        $c3->setRegion($r);
        $c3->setHasReferenceLab(true);
        $c3->setHasNationalLab(true);

        $c4 = new Country();
        $c4->setCode('IN');
        $c4->setName('India');
        $c4->setIsActive(true);
        $c4->setRegion($r2);
        $c4->setHasReferenceLab(true);
        $c4->setHasNationalLab(true);

        $c5 = new Country();
        $c5->setCode('MA');
        $c5->setName('Malaysia');
        $c5->setIsActive(true);
        $c5->setRegion($r2);
        $c5->setHasReferenceLab(true);
        $c5->setHasNationalLab(true);

        $c6 = new Country();
        $c6->setCode('TH');
        $c6->setName('Thailand');
        $c6->setIsActive(true);
        $c6->setRegion($r2);
        $c6->setHasReferenceLab(true);
        $c6->setHasNationalLab(true);

        $sc = new \NS\SentinelBundle\Form\Types\SurveillanceConducted(\NS\SentinelBundle\Form\Types\SurveillanceConducted::BOTH);

        $s = new Site();
        $s->setName('Alberta Childrens Hospital');
        $s->setCountry($c);
        $s->setCode('ALBCHLD');
        $s->setSurveillanceConducted($sc);
        $s->setActive(true);

        $s1 = new Site();
        $s1->setName('Toronto Childrens Hospital');
        $s1->setCountry($c);
        $s1->setCode('TCHLD');
        $s1->setSurveillanceConducted($sc);
        $s1->setActive(true);

        $s2 = new Site();
        $s2->setName('Shriners Childrens Hospital');
        $s2->setCountry($c);
        $s2->setCode('SHCHLD');
        $s2->setSurveillanceConducted($sc);
        $s2->setActive(true);

        $s3 = new Site();
        $s3->setName('Seattle Grace Hospital');
        $s3->setCountry($c2);
        $s3->setCode('SGH');
        $s3->setSurveillanceConducted($sc);
        $s3->setActive(true);

        $s4 = new Site();
        $s4->setName('Mexico General Hospital');
        $s4->setCountry($c3);
        $s4->setCode("MGH");
        $s4->setSurveillanceConducted($sc);
        $s4->setActive(true);

        $s5 = new Site();
        $s5->setName('New Dehli Main Hospital');
        $s5->setCountry($c4);
        $s5->setCode("NDMH");
        $s5->setSurveillanceConducted($sc);
        $s5->setActive(true);

        $s6 = new Site();
        $s6->setName('Thailand Main Hospital');
        $s6->setCountry($c6);
        $s6->setCode("TMH");
        $s6->setSurveillanceConducted($sc);
        $s6->setActive(true);

        $manager->persist($r);
        $manager->persist($r2);
        $manager->persist($c);
        $manager->persist($c2);
        $manager->persist($c3);
        $manager->persist($c4);
        $manager->persist($c5);
        $manager->persist($c6);
        
        $manager->persist($s);
        $manager->persist($s1);
        $manager->persist($s2);
        $manager->persist($s3);
        $manager->persist($s4);
        $manager->persist($s5);
        $manager->persist($s6);
        
        $manager->flush();
        
        $this->addReference('region-na', $r);
        $this->addReference('country-ca',$c);
        $this->addReference('region-in', $r2);
        $this->addReference('country-us',$c2);
        $this->addReference('site-alberta', $s);
        $this->addReference('site-shriners', $s2);
        $this->addReference('site-toronto', $s1);
        $this->addReference('site-seattle', $s3);
        $this->addReference('site-mexico', $s4);
    }
    
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
