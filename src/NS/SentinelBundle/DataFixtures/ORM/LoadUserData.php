<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Form\Types\Role;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $adminUser = new User();
        $adminUser->setEmail('superadmin@noblet.ca');
        $adminUser->setName('NS Admin User');
        $adminUser->resetSalt();
        $adminUser->setIsAdmin(true);
        
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($adminUser);
                
        $adminUser->setPassword($encoder->encodePassword("GnatAndDaveInIndia",$adminUser->getSalt()));

        $manager->persist($adminUser);

        $naUser = new User();
        $naUser->setIsActive(true);
        $naUser->setEmail('na@noblet.ca');
        $naUser->setName('NA User');
        $naUser->resetSalt();
        $naUser->setPassword($encoder->encodePassword("1234567-na",$naUser->getSalt()));
        $acl = new ACL();
        $acl->setUser($naUser);
        $acl->setType(new Role(Role::REGION));
        $acl->setObjectId($this->getReference('region-na')->getId());

        $manager->persist($naUser);
        $manager->persist($acl);

        $usUser = new User();
        $usUser->setIsActive(true);
        $usUser->setEmail('us@noblet.ca');
        $usUser->setName('US User');
        $usUser->resetSalt();
        $usUser->setPassword($encoder->encodePassword("1234567-us",$usUser->getSalt()));
        $acl = new ACL();
        $acl->setUser($usUser);
        $acl->setType(new Role(Role::COUNTRY));
        $acl->setObjectId($this->getReference('country-us')->getId());
        
        $manager->persist($usUser);
        $manager->persist($acl);
        
        $caUser = new User();
        $caUser->setIsActive(true);
        $caUser->setEmail('ca@noblet.ca');
        $caUser->setName('Canada User');
        $caUser->resetSalt();
        $caUser->setPassword($encoder->encodePassword("1234567-ca",$caUser->getSalt()));
        $acl = new ACL();
        $acl->setUser($caUser);
        $acl->setType(new Role(Role::COUNTRY));
        $acl->setObjectId($this->getReference('country-ca')->getId());

        $manager->persist($caUser);
        $manager->persist($acl);
        
        $siteSUser = new User();
        $siteSUser->setIsActive(true);
        $siteSUser->setEmail('site-alberta@noblet.ca');
        $siteSUser->setName('Alberta Site User');
        $siteSUser->resetSalt();
        $siteSUser->setPassword($encoder->encodePassword("1234567-alberta",$siteSUser->getSalt()));
        $acl = new ACL();
        $acl->setUser($siteSUser);
        $acl->setType(new Role(Role::SITE));
        $acl->setObjectId($this->getReference('site-alberta')->getId());

        $manager->persist($siteSUser);
        $manager->persist($acl);
        
        $siteSUser = new User();
        $siteSUser->setIsActive(true);
        $siteSUser->setEmail('site-seattle@noblet.ca');
        $siteSUser->setName('Seattle Site User');
        $siteSUser->resetSalt();
        $siteSUser->setPassword($encoder->encodePassword("1234567-seattle",$siteSUser->getSalt()));
        $acl = new ACL();
        $acl->setUser($siteSUser);
        $acl->setType(new Role(Role::SITE));
        $acl->setObjectId($this->getReference('site-seattle')->getId());

        $manager->persist($siteSUser);
        $manager->persist($acl);        

        
        $mUser = new User();
        $mUser->setIsActive(true);
        $mUser->setEmail('site-multiple@noblet.ca');
        $mUser->setName('Multiple User');
        $mUser->resetSalt();
        $mUser->setPassword($encoder->encodePassword("1234567-multi",$mUser->getSalt()));
        $acl = new ACL();
        $acl->setUser($mUser);
        $acl->setType(new Role(Role::SITE));
        $acl->setObjectId($this->getReference('site-alberta')->getId());

        $acl1 = new ACL();
        $acl1->setUser($mUser);
        $acl1->setType(new Role(Role::SITE));
        $acl1->setObjectId($this->getReference('site-toronto')->getId());
        
        $manager->persist($mUser);
        $manager->persist($acl);
        $manager->persist($acl1);
        
        $manager->flush();
    }
    
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
