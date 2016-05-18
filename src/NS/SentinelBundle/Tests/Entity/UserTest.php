<?php

namespace NS\SentinelBundle\Tests\Entity;

use \NS\SentinelBundle\Entity\User;
use \NS\SentinelBundle\Entity\ACL;
use \NS\SentinelBundle\Form\Types\Role;

/**
 * Description of UserTest
 *
 * @author gnat
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testApiUser()
    {
        $user = new User();
        $acl = new ACL();
        $acl->setType(new Role(Role::COUNTRY_API));
        $user->addAcl($acl);

        $this->assertEquals(array('ROLE_COUNTRY_API', 'ROLE_CAN_CREATE_CASE', 'ROLE_CAN_CREATE_LAB', 'ROLE_CAN_CREATE_NL_LAB'), $user->getRoles());
    }

    /**
     * @param User $user
     * @param array $expectedRoles
     * @param bool $isOnlyAdmin
     *
     * @dataProvider getUsersWithRoles
     */
    public function testRoles(User $user, array $expectedRoles, $isOnlyAdmin)
    {
        $this->assertEquals($expectedRoles, $user->getRoles());
        $this->assertEquals($isOnlyAdmin,$user->isOnlyAdmin());
    }

    public function getUsersWithRoles()
    {
        $superAdmin = new User();
        $superAdmin->setAdmin(true);

        $acl = new ACL();
        $acl->setType(new Role(Role::REGION));
        $regionUser = new User();
        $regionUser->addAcl(clone $acl);
        $regionAdminUser = clone $regionUser;
        $regionAdminUser->setAdmin(true);

        $acl->setType(new Role(Role::COUNTRY));
        $countryUser = new User();
        $countryUser->addAcl(clone $acl);
        $countryAdminUser = clone $countryUser;
        $countryAdminUser->setAdmin(true);

        $acl->setType(new Role(Role::LAB));
        $siteUser = new User();
        $siteUser->setAdmin(true);
        $siteUser->addAcl($acl);

        return array(
            array($superAdmin,array('ROLE_ADMIN','ROLE_SUPER_ADMIN'),true),
            array($regionUser,array('ROLE_REGION'),false),
            array($regionAdminUser,array('ROLE_REGION','ROLE_ADMIN','ROLE_SONATA_REGION_ADMIN'),false),
            array($countryUser,array('ROLE_COUNTRY'),false),
            array($countryAdminUser,array('ROLE_COUNTRY','ROLE_ADMIN','ROLE_SONATA_COUNTRY_ADMIN'),false),
            array($siteUser,array('ROLE_LAB'),false),
        );
    }
}
