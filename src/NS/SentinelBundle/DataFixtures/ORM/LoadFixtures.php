<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use NS\SentinelBundle\DataFixtures\Alice\UserProcessor;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadFixtures
 *
 * @author gnat
 */
class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var
     */
    private $container;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $files = [
            __DIR__ . '/../Alice/region.yml',
            __DIR__ . '/../Alice/users.yml',
            __DIR__ . '/../Alice/cases.yml',
            __DIR__ . '/../../../ApiBundle/DataFixtures/Alice/clients.yml',
        ];

        $options    = ['providers' => [$this->container->get('ns_sentinel.misc_provider')]];
        $processors = [new UserProcessor($this->container->get('security.encoder_factory'))];

        Fixtures::load($files, $manager, $options, $processors);
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
