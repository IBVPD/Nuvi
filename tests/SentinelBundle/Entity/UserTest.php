<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Form\Types\Role;
use PHPUnit\Framework\TestCase;

/**
 * Description of UserTest
 *
 * @author gnat
 */
class UserTest extends TestCase
{
    public function testApiUser(): void
    {
        $user = new User();
        $acl = new ACL();
        $acl->setType(new Role(Role::COUNTRY));
        $acl->setOptions(['api']);
        $user->addAcl($acl);

        $this->assertEquals(['ROLE_COUNTRY', 'ROLE_API'], $user->getRoles());
    }

    /**
     * @param User $user
     * @param array $expectedRoles
     * @param bool $isOnlyAdmin
     *
     * @dataProvider getUsersWithRoles
     */
    public function testRoles(User $user, array $expectedRoles, $isOnlyAdmin): void
    {
        $this->assertEquals($expectedRoles, $user->getRoles());
        $this->assertEquals($isOnlyAdmin,$user->isOnlyAdmin());
    }

    public function getUsersWithRoles(): array
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

        return [
            [$superAdmin, ['ROLE_ADMIN','ROLE_SUPER_ADMIN'],true],
            [$regionUser, ['ROLE_REGION'],false],
            [$regionAdminUser, ['ROLE_REGION','ROLE_ADMIN','ROLE_SONATA_REGION_ADMIN'],false],
            [$countryUser, ['ROLE_COUNTRY'],false],
            [$countryAdminUser, ['ROLE_COUNTRY','ROLE_ADMIN','ROLE_SONATA_COUNTRY_ADMIN'],false],
            [$siteUser, ['ROLE_LAB'],false],
        ];
    }
}
