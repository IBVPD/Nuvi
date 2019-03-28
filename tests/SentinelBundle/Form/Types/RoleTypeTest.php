<?php

namespace NS\SentinelBundle\Tests\Form\Types;

use NS\SentinelBundle\Form\Types\Role;
use Symfony\Component\Form\Test\TypeTestCase;
use UnexpectedValueException;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Entity\Region;

/**
 * Description of RoleTypeTest
 *
 * @author gnat
 */
class RoleTypeTest extends TypeTestCase
{

    /**
     * @expectedException UnexpectedValueException
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
            ['roleStr' => 'ROLE_REGION', 'entity' => Region::class],
            ['roleStr' => 'ROLE_COUNTRY', 'entity' => Country::class],
            ['roleStr' => 'ROLE_SITE', 'entity' => Site::class],
            ['roleStr' => 'ROLE_LAB', 'entity' => Site::class],
            ['roleStr' => 'ROLE_NL_LAB', 'entity' => Country::class],
            ['roleStr' => 'ROLE_RRL_LAB', 'entity' => Country::class],
        ];
    }

    public function getCredentialProvider()
    {
        return [
            ['ROLE_REGION', ['ROLE_REGION']],
            ['ROLE_COUNTRY', ['ROLE_COUNTRY']],
            ['ROLE_SITE', ['ROLE_SITE']],
            ['ROLE_LAB', ['ROLE_LAB']],
            ['ROLE_NL_LAB', ['ROLE_NL_LAB']],
            ['ROLE_RRL_LAB', ['ROLE_RRL_LAB']],
        ];
    }
}
