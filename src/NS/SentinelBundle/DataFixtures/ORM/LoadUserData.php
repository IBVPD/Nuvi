<?php

namespace NS\SentinelBundle\DataFixtures\ORM;

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Doctrine\Common\Persistence\ObjectManager;
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
        $adminUser->setIsActive(true);

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

        $references = array();

        foreach($this->getCountryData() as $data)
        {
            $user = new User();
            $user->setIsActive(true);
            $user->setEmail($data['email']);
            $user->setName($data['name']);
            $user->resetSalt();
            $user->setPassword($encoder->encodePassword($data['password'],$user->getSalt()));
            $user->setCanCreateCases($data['can_create_cases']);
            $user->setCanCreateLabs($data['can_create_labs']);

            $acl = new ACL();
            $acl->setUser($user);
            $acl->setType(new Role((isset($data['role'])? $data['role']:Role::COUNTRY)));
            $acl->setObjectId($this->getReference($data['ref-name'])->getId());

            $manager->persist($user);
            $manager->persist($acl);

            if(isset($data['reference']))
                $references[$data['reference']] = $user;
        }

        foreach($this->getSiteData() as $data)
        {
            $user = new User();
            $user->setIsActive(true);
            $user->setEmail($data['email']);
            $user->setName($data['name']);
            $user->resetSalt();
            $user->setPassword($encoder->encodePassword($data['password'],$user->getSalt()));
            $user->setCanCreateCases($data['can_create_cases']);
            $user->setCanCreateLabs($data['can_create_labs']);

            $acl = new ACL();
            $acl->setUser($user);
            $acl->setType(new Role(Role::SITE));
            $acl->setObjectId($this->getReference($data['ref-name'])->getId());
            $manager->persist($user);
            $manager->persist($acl);

            if(isset($data['reference']))
                $references[$data['reference']] = $user;
        }

        foreach($this->getLabData() as $data)
        {
            $user = new User();
            $user->setIsActive(true);
            $user->setEmail($data['email']);
            $user->setName($data['name']);
            $user->resetSalt();
            $user->setPassword($encoder->encodePassword($data['password'],$user->getSalt()));

            $acl = new ACL();
            $acl->setUser($user);
            $acl->setType(new Role($data['role_type']));
            $acl->setObjectId($this->getReference($data['ref-name'])->getId());
            $manager->persist($user);
            $manager->persist($acl);

            if(isset($data['reference']))
                $references[$data['reference']] = $user;
        }

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

        foreach($references as $name => $object)
            $this->addReference ($name, $object);
    }

    public function getCountryData()
    {
        return array(
            array(
                    'name'      => 'US User',
                    'password'  => '1234567-us',
                    'email'     => 'us@noblet.ca',
                    'ref-name'  => 'country-us',
                    'can_create_cases' => false,
                    'can_create_labs' => false,
                 ),
            array(
                    'name'      => 'Canada User',
                    'password'  => '1234567-ca',
                    'email'     => 'ca@noblet.ca',
                    'ref-name'  => 'country-ca',
                    'can_create_cases' => false,
                    'can_create_labs' => false,
                ),
            array(
                    'name'      => 'Canada Create User',
                    'password'  => '1234567-ca-create',
                    'email'     => 'ca-create@noblet.ca',
                    'ref-name'  => 'country-ca',
                    'can_create_cases' => true,
                    'can_create_labs' => false,
                 ),
            array(
                    'name'      => 'Canada Create User',
                    'password'  => '1234567-ca-clab',
                    'email'     => 'ca-clab@noblet.ca',
                    'ref-name'  => 'country-ca',
                    'can_create_cases' => true,
                    'can_create_labs' => true,
                 ),
            array(
                    'name'      => 'Canada Create User',
                    'password'  => '1234567-ca-crrl',
                    'email'     => 'ca-crrl@noblet.ca',
                    'ref-name'  => 'country-ca',
                    'can_create_cases' => true,
                    'can_create_labs' => true,
                 ),
            array(
                    'name'      => 'Canada Create User',
                    'password'  => '1234567-ca-cnl',
                    'email'     => 'ca-cnl@noblet.ca',
                    'ref-name'  => 'country-ca',
                    'can_create_cases' => true,
                    'can_create_labs' => true,
                 ),
            array(
                    'name'      => 'Canada Full User',
                    'password'  => '1234567-ca-full',
                    'email'     => 'ca-full@noblet.ca',
                    'ref-name'  => 'country-ca',
                    'can_create_cases' => true,
                    'can_create_labs' => true,
                 ),
            array(
                    'name'      => 'Canada Api User',
                    'password'  => '1234567-ca-api',
                    'email'     => 'ca-api@noblet.ca',
                    'ref-name'  => 'country-ca',
                    'reference' => 'country-ca-api',
                    'can_create_cases' => true,
                    'can_create_labs' => true,
                    'role'      => Role::COUNTRY_API,
                 ),
        );
    }

    public function getSiteData()
    {
        return array(
            array('name'=>'Alberta Site User','password'=>'1234567-alberta','email'=>'site-alberta@noblet.ca','ref-name'=>'site-alberta',
                    'can_create_cases' => true,
                    'can_create_labs' => false,),
            array('name'=>'Seattle Site User','password'=>'1234567-seattle','email'=>'site-seattle@noblet.ca','ref-name'=>'site-seattle',
                    'can_create_cases' => true,
                    'can_create_labs' => false,),
            array('name'=>'Shriners Site User','password'=>'1234567-shriner','email'=>'site-shriner@noblet.ca','ref-name'=>'site-shriners',
                    'can_create_cases' => true,
                    'can_create_labs' => true,),
            array('name'=>'Toronto Site User','password'=>'1234567-toronto','email'=>'site-toronto@noblet.ca','ref-name'=>'site-toronto',
                    'can_create_cases' => true,
                    'can_create_labs' => false,),
            array('name'=>'Mexico Site User','password'=>'1234567-mexico','email'=>'site-mexico@noblet.ca','ref-name'=>'site-mexico',
                    'can_create_cases' => true,
                    'can_create_labs' => false,),
        );
    }

    public function getLabData()
    {
        return array(
            array('name'=>'Alberta RRL User','password'=>'1234567-alberta-rrl','email'=>'rrl-alberta@noblet.ca','ref-name'=>'site-alberta',
                    'role_type'        => ROLE::LAB,
                    'can_create_cases' => false,
                    'can_create_labs'  => false,),
            array('name'=>'Alberta NL User','password'=>'1234567-alberta-nl','email'=>'nl-alberta@noblet.ca','ref-name'=>'site-alberta',
                    'role_type'        => ROLE::LAB,
                    'can_create_cases' => false,
                    'can_create_labs'  => false,),
            array('name'=>'Alberta Lab User','password'=>'1234567-alberta-lab','email'=>'lab-alberta@noblet.ca','ref-name'=>'site-alberta',
                    'role_type'        => ROLE::LAB,
                    'can_create_cases' => false,
                    'can_create_labs'  => false,),
        );
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
