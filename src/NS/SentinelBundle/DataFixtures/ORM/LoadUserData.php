<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Form\Type\Role;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $adminUser = new User();
        $adminUser->setEmail('superadmin@noblet.ca');
        $adminUser->setName('NS Admin User');
        
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($adminUser);
        $adminUser->setSalt(md5(time().$adminUser->getEmail().$adminUser->getName()));
        
        $password = $encoder->encodePassword("19mada98",$adminUser->getSalt());
        $adminUser->setPassword($password);
//        $acl = new ACL();
//        $acl->setObjectId(1);
//        $acl->setUser($adminUser);
//        $acl->setType(1);
//        
//        $adminUser->addAcl($acl);
        $manager->persist($adminUser);
//        $manager->persist($acl);
        
        $manager->flush();
        
//        $this->addReference('testuser', $testUser);
    }
    
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
