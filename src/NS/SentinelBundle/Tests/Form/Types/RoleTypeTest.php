<?php

namespace NS\SentinelBundle\Tests\Form\Types;

use \NS\SentinelBundle\Form\Types\Role;
use \Symfony\Component\Form\Test\TypeTestCase;

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
     */
    public function testGetCredential($roleStr)
    {
        $role = new Role($roleStr);
        $this->assertEquals(array($roleStr), $role->getAsCredential());
    }

    public function testEmptyCredential()
    {
        $role = new Role();
        $this->assertNull($role->getAsCredential());
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
     */
    public function testGetClassMatch($roleStr, $entity)
    {
        $role = new Role($roleStr);
        $this->assertEquals($entity, $role->getClassMatch());
    }

    public function getClassMatchProvider()
    {
        return array(
            array('roleStr' => 'ROLE_REGION', 'entity' => 'NS\SentinelBundle\Entity\Region'),
            array('roleStr' => 'ROLE_REGION_API', 'entity' => 'NS\SentinelBundle\Entity\Region'),
            array('roleStr' => 'ROLE_REGION_IMPORT', 'entity' => 'NS\SentinelBundle\Entity\Region'),
            array('roleStr' => 'ROLE_COUNTRY', 'entity' => 'NS\SentinelBundle\Entity\Country'),
            array('roleStr' => 'ROLE_COUNTRY_IMPORT', 'entity' => 'NS\SentinelBundle\Entity\Country'),
            array('roleStr' => 'ROLE_COUNTRY_API', 'entity' => 'NS\SentinelBundle\Entity\Country'),
            array('roleStr' => 'ROLE_SITE', 'entity' => 'NS\SentinelBundle\Entity\Site'),
            array('roleStr' => 'ROLE_SITE_IMPORT', 'entity' => 'NS\SentinelBundle\Entity\Site'),
            array('roleStr' => 'ROLE_SITE_API', 'entity' => 'NS\SentinelBundle\Entity\Site'),
            array('roleStr' => 'ROLE_LAB', 'entity' => 'NS\SentinelBundle\Entity\Site'),
            array('roleStr' => 'ROLE_NL_LAB', 'entity' => 'NS\SentinelBundle\Entity\Country'),
            array('roleStr' => 'ROLE_RRL_LAB', 'entity' => 'NS\SentinelBundle\Entity\Country'),
        );
    }

    public function getCredentialProvider()
    {
        return array(
            array('roleStr' => 'ROLE_REGION'),
            array('roleStr' => 'ROLE_REGION_API'),
            array('roleStr' => 'ROLE_REGION_IMPORT'),
            array('roleStr' => 'ROLE_COUNTRY'),
            array('roleStr' => 'ROLE_COUNTRY_IMPORT'),
            array('roleStr' => 'ROLE_COUNTRY_API'),
            array('roleStr' => 'ROLE_SITE'),
            array('roleStr' => 'ROLE_SITE_IMPORT'),
            array('roleStr' => 'ROLE_SITE_API'),
            array('roleStr' => 'ROLE_LAB'),
            array('roleStr' => 'ROLE_NL_LAB'),
            array('roleStr' => 'ROLE_RRL_LAB'),
        );
    }
}