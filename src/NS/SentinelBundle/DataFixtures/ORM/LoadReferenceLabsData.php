<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Doctrine\Common\Persistence\ObjectManager;
use \NS\SentinelBundle\Entity\ReferenceLab;
use \NS\SentinelBundle\Form\Types\SurveillanceConducted;
use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadReferenceLabsData
 *
 * @author gnat
 */
class LoadReferenceLabsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function getOrder()
    {
        return 11;
    }

    public function load(ObjectManager $manager)
    {
        $lab = new ReferenceLab();
        $lab->setUserId('1');
        $lab->setName('Reference Lab 1');
        $lab->setLocation('Some location');
        $lab->setCountry($this->getReference('country-ca'));
        $lab->setType(new SurveillanceConducted(SurveillanceConducted::IBD));

        $manager->persist($lab);
        $manager->flush();
        $this->setReference('reference-lab', $lab);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
