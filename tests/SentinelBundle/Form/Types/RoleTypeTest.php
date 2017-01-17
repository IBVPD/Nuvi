<?php

namespace NS\SentinelBundle\Tests\Form\Types;

use NS\SentinelBundle\Form\Types\Role;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Description of RoleTypeTest
 *
 * @author gnat
 */
class RoleTypeTest extends TypeTestCase
{

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidRoleMap()
    {
        new Role('ROLE_REGION2');
    }

    /**
     * @dataProvider getCredentialProvider
     * @param $roleStr
     * @param $expected
     */
    public function testGetCredential($roleStr, $expected)
    {
        $role = new Role($roleStr);
        $this->assertEquals($expected, $role->getAsCredential());
    }

    public function testEmptyCredential()
    {
        $role = new Role();
        $this->assertEquals([],$role->getAsCredential());
        $this->assertNull($role->getClassMatch());
    }

    public function testEqualTo()
    {
        $role      = new Role(Role::COUNTRY);
        $cntryRole = clone $role;
        $this->assertTrue($role->equal('ROLE_COUNTRY'));
        $this->assertTrue($role->equal(2));
        $this->assertTrue($role->equal($cntryRole));

        $siteRole = new Role(Role::SITE);
        $this->assertFalse($role->equal(3));
        $this->assertFalse($role->equal('3'));
        $this->assertFalse($role->equal('ROLE_REGION'));
        $this->assertFalse($role->equal($siteRole));
        $this->assertFalse($role->equal(false));
    }

    /**
     * @dataProvider getClassMatchProvider
     * @param $roleStr
     * @param $entity
     */
    public function testGetClassMatch($roleStr, $entity)
    {
        $role = new Role($roleStr);
        $this->assertEquals($entity, $role->getClassMatch());
    }

    public function getClassMatchProvider()
    {
        return [
            ['roleStr' => 'ROLE_REGION', 'entity' => 'NS\SentinelBundle\Entity\Region'],
            ['roleStr' => 'ROLE_REGION_API', 'entity' => 'NS\SentinelBundle\Entity\Region'],
            ['roleStr' => 'ROLE_REGION_IMPORT', 'entity' => 'NS\SentinelBundle\Entity\Region'],
            ['roleStr' => 'ROLE_COUNTRY', 'entity' => 'NS\SentinelBundle\Entity\Country'],
            ['roleStr' => 'ROLE_COUNTRY_IMPORT', 'entity' => 'NS\SentinelBundle\Entity\Country'],
            ['roleStr' => 'ROLE_COUNTRY_API', 'entity' => 'NS\SentinelBundle\Entity\Country'],
            ['roleStr' => 'ROLE_SITE', 'entity' => 'NS\SentinelBundle\Entity\Site'],
            ['roleStr' => 'ROLE_SITE_IMPORT', 'entity' => 'NS\SentinelBundle\Entity\Site'],
            ['roleStr' => 'ROLE_SITE_API', 'entity' => 'NS\SentinelBundle\Entity\Site'],
            ['roleStr' => 'ROLE_LAB', 'entity' => 'NS\SentinelBundle\Entity\Site'],
            ['roleStr' => 'ROLE_NL_LAB', 'entity' => 'NS\SentinelBundle\Entity\Country'],
            ['roleStr' => 'ROLE_RRL_LAB', 'entity' => 'NS\SentinelBundle\Entity\Country'],
        ];
    }

    public function getCredentialProvider()
    {
        return [
            ['ROLE_REGION', ['ROLE_REGION']],
            ['ROLE_REGION_API', ['ROLE_REGION_API','ROLE_CAN_CREATE_CASE','ROLE_CAN_CREATE_LAB','ROLE_CAN_CREATE_NL_LAB']],
            ['ROLE_REGION_IMPORT', ['ROLE_REGION_IMPORT']],
            ['ROLE_COUNTRY', ['ROLE_COUNTRY']],
            ['ROLE_COUNTRY_IMPORT', ['ROLE_COUNTRY_IMPORT']],
            ['ROLE_COUNTRY_API', ['ROLE_COUNTRY_API','ROLE_CAN_CREATE_CASE','ROLE_CAN_CREATE_LAB','ROLE_CAN_CREATE_NL_LAB']],
            ['ROLE_SITE', ['ROLE_SITE']],
            ['ROLE_SITE_IMPORT', ['ROLE_SITE_IMPORT']],
            ['ROLE_SITE_API', ['ROLE_SITE_API','ROLE_CAN_CREATE_CASE','ROLE_CAN_CREATE_LAB']],
            ['ROLE_LAB', ['ROLE_LAB']],
            ['ROLE_NL_LAB', ['ROLE_NL_LAB']],
            ['ROLE_RRL_LAB', ['ROLE_RRL_LAB']],
        ];
    }
}
