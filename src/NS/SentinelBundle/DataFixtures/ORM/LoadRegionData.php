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
        $region = new Region();
        $region->setName('North America');
        $region->setCode('NA');

        $region2 = new Region();
        $region2->setName('South East Asia');
        $region2->setCode('SEA');

        $country = new Country();
        $country->setCode('CA');
        $country->setName('Canada');
        $country->setIsActive(true);
        $country->setRegion($region);
        $country->setHasReferenceLab(true);
        $country->setHasNationalLab(true);

        $country2 = new Country();
        $country2->setCode('US');
        $country2->setName('United States');
        $country2->setIsActive(true);
        $country2->setRegion($region);
        $country2->setHasReferenceLab(true);
        $country2->setHasNationalLab(true);

        $country3 = new Country();
        $country3->setCode('MX');
        $country3->setName('Mexico');
        $country3->setIsActive(true);
        $country3->setRegion($region);
        $country3->setHasReferenceLab(true);
        $country3->setHasNationalLab(true);

        $country4 = new Country();
        $country4->setCode('IN');
        $country4->setName('India');
        $country4->setIsActive(true);
        $country4->setRegion($region2);
        $country4->setHasReferenceLab(true);
        $country4->setHasNationalLab(true);

        $country5 = new Country();
        $country5->setCode('MA');
        $country5->setName('Malaysia');
        $country5->setIsActive(true);
        $country5->setRegion($region2);
        $country5->setHasReferenceLab(true);
        $country5->setHasNationalLab(true);

        $country6 = new Country();
        $country6->setCode('TH');
        $country6->setName('Thailand');
        $country6->setIsActive(true);
        $country6->setRegion($region2);
        $country6->setHasReferenceLab(true);
        $country6->setHasNationalLab(true);

        $surveillance = new \NS\SentinelBundle\Form\Types\SurveillanceConducted(\NS\SentinelBundle\Form\Types\SurveillanceConducted::BOTH);

        $site = new Site();
        $site->setName('Alberta Childrens Hospital');
        $site->setCountry($country);
        $site->setCode('ALBCHLD');
        $site->setSurveillanceConducted($surveillance);
        $site->setActive(true);

        $site1 = new Site();
        $site1->setName('Toronto Childrens Hospital');
        $site1->setCountry($country);
        $site1->setCode('TCHLD');
        $site1->setSurveillanceConducted($surveillance);
        $site1->setActive(true);

        $site2 = new Site();
        $site2->setName('Shriners Childrens Hospital');
        $site2->setCountry($country);
        $site2->setCode('SHCHLD');
        $site2->setSurveillanceConducted($surveillance);
        $site2->setActive(true);

        $site3 = new Site();
        $site3->setName('Seattle Grace Hospital');
        $site3->setCountry($country2);
        $site3->setCode('SGH');
        $site3->setSurveillanceConducted($surveillance);
        $site3->setActive(true);

        $site4 = new Site();
        $site4->setName('Mexico General Hospital');
        $site4->setCountry($country3);
        $site4->setCode("MGH");
        $site4->setSurveillanceConducted($surveillance);
        $site4->setActive(true);

        $site5 = new Site();
        $site5->setName('New Dehli Main Hospital');
        $site5->setCountry($country4);
        $site5->setCode("NDMH");
        $site5->setSurveillanceConducted($surveillance);
        $site5->setActive(true);

        $site6 = new Site();
        $site6->setName('Thailand Main Hospital');
        $site6->setCountry($country6);
        $site6->setCode("TMH");
        $site6->setSurveillanceConducted($surveillance);
        $site6->setActive(true);

        $manager->persist($region);
        $manager->persist($region2);
        $manager->persist($country);
        $manager->persist($country2);
        $manager->persist($country3);
        $manager->persist($country4);
        $manager->persist($country5);
        $manager->persist($country6);

        $manager->persist($site);
        $manager->persist($site1);
        $manager->persist($site2);
        $manager->persist($site3);
        $manager->persist($site4);
        $manager->persist($site5);
        $manager->persist($site6);

        $manager->flush();
        
        $this->addReference('region-na', $region);
        $this->addReference('country-ca', $country);
        $this->addReference('region-in', $region2);
        $this->addReference('country-us', $country2);
        $this->addReference('site-alberta', $site);
        $this->addReference('site-shriners', $site2);
        $this->addReference('site-toronto', $site1);
        $this->addReference('site-seattle', $site3);
        $this->addReference('site-mexico', $site4);
    }
    
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
