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

        $this->assertEquals(array('ROLE_COUNTRY_API','ROLE_CAN_CREATE_CASE', 'ROLE_CAN_CREATE_LAB', 'ROLE_CAN_CREATE_NL_LAB'),$user->getRoles());
    }
}
