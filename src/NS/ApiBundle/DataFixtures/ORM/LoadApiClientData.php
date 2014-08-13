<?php

namespace NS\ApiBundle\DataFixtures\ORM;

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use NS\ApiBundle\Entity\Client;
use OAuth2\OAuth2;

class LoadApiClientData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function getOrder()
    {
        return 10;
    }

    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setName('Api Test Client');
        $client->setRedirectUris(array('http://localhost/authorize'));
        $client->setAllowedGrantTypes(array(OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS));
        $client->setUser($this->getReference('country-ca-api'));

        $manager->persist($client);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
