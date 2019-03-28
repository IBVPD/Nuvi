<?php

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Form\Types\Role;
use NS\SentinelBundle\Validators\ACLValidator;
use NS\SentinelBundle\Validators\ACL as ACLConstraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ACLValidatorTest extends TestCase
{
    /**
     * @param ACL $acl
     * @dataProvider getNonDeprecatedRoles
     */
    public function testNoDeprecatedRole(ACL $acl): void
    {
        $context = $this->createMock(ExecutionContextInterface::class);

        $context
            ->expects($this->never())
            ->method('buildViolation');

        $constraint = new ACLConstraint();
        $validator = new ACLValidator();
        $validator->initialize($context);

        $validator->validate($acl, $constraint);
    }

    public function getNonDeprecatedRoles(): array
    {
        $aclRegion  = new ACL();
        $aclRegion->setType(new Role(Role::REGION));

        $aclCountry = new ACL();
        $aclCountry->setType(new Role(Role::COUNTRY));

        $aclSite    = new ACL();
        $aclSite->setType(new Role(Role::SITE));

        $aclLab     = new ACL();
        $aclLab->setType(new Role(Role::LAB));

        $aclNL      = new ACL();
        $aclNL->setType(new Role(Role::NL_LAB));

        $aclRRL     = new ACL();
        $aclRRL->setType(new Role(Role::RRL_LAB));

        return [
            [$aclRegion],
            [$aclCountry],
            [$aclSite],
            [$aclLab],
            [$aclNL],
            [$aclRRL],
        ];
    }
}
