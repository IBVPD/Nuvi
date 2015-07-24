<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Doctrine\Common\DataFixtures\FixtureInterface;
use \Doctrine\Common\Persistence\ObjectManager;
use \Nelmio\Alice\Fixtures;
use \NS\SentinelBundle\DataFixtures\Alice\UserProcessor;
use \NS\SentinelBundle\Form\Types\CSFAppearance;
use \NS\SentinelBundle\Form\Types\CXRResult;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\FourDoses;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\Role;
use \NS\SentinelBundle\Form\Types\SurveillanceConducted;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\VaccinationReceived;
use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadFixtures
 *
 * @author gnat
 */
class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $files = array(
            __DIR__ . '/../Alice/region.yml',
            __DIR__ . '/../Alice/users.yml',
            __DIR__ . '/../Alice/cases.yml',
            __DIR__ . '/../../../ApiBundle/DataFixtures/Alice/clients.yml',
        );

        $options    = array('providers' => array($this));
        $processors = array(new UserProcessor($this->container->get('security.encoder_factory')));

        Fixtures::load($files, $manager, $options, $processors);
    }

    public function surveillanceConducted()
    {
        return new SurveillanceConducted(SurveillanceConducted::BOTH);
    }

    public function done()
    {
        $choices = array(
            new TripleChoice(TripleChoice::YES),
            new TripleChoice(TripleChoice::NO),
            new TripleChoice(TripleChoice::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    public function gender()
    {
        $choices = array(
            new Gender(Gender::MALE),
            new Gender(Gender::FEMALE),
        );

        return $choices[array_rand($choices)];
    }

    public function diagnosis()
    {
        $choices = array(
            new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS),
            new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA),
            new Diagnosis(Diagnosis::SUSPECTED_SEPSIS),
            new Diagnosis(Diagnosis::OTHER),
        );

        return $choices[array_rand($choices)];
    }

    public function cxrResult()
    {
        $choices = array(
            new CXRResult(CXRResult::CONSISTENT),
            new CXRResult(CXRResult::NORMAL),
            new CXRResult(CXRResult::INCONCLUSIVE),
            new CXRResult(CXRResult::OTHER),
            new CXRResult(CXRResult::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    public function vaccinationReceived()
    {
        $choices = array(
            new VaccinationReceived(VaccinationReceived::NO),
            new VaccinationReceived(VaccinationReceived::YES_HISTORY),
            new VaccinationReceived(VaccinationReceived::YES_CARD),
            new VaccinationReceived(VaccinationReceived::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    public function fourDoses()
    {
        $choices = array(
            new FourDoses(FourDoses::ONE),
            new FourDoses(FourDoses::TWO),
            new FourDoses(FourDoses::THREE),
            new FourDoses(FourDoses::FOUR),
            new FourDoses(FourDoses::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    public function csfAppearance()
    {
        $choices = array(
            new CSFAppearance(CSFAppearance::CLEAR),
            new CSFAppearance(CSFAppearance::TURBID),
            new CSFAppearance(CSFAppearance::BLOODY),
            new CSFAppearance(CSFAppearance::XANTHROCHROMIC),
            new CSFAppearance(CSFAppearance::OTHER),
            new CSFAppearance(CSFAppearance::NOT_ASSESSED),
            new CSFAppearance(CSFAppearance::UNKNOWN),
        );

        return $choices[array_rand($choices)];
    }

    public function regionRole()
    {
        return new Role(Role::REGION);
    }

    public function countryRole()
    {
        return new Role(Role::COUNTRY);
    }

    public function countryApiRole()
    {
        return new Role(Role::COUNTRY_API);
    }

    public function countryExportRole()
    {
        return new Role(Role::COUNTRY_IMPORT);
    }

    public function siteRole()
    {
        return new Role(Role::SITE);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}