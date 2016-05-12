<?php

namespace NS\SentinelBundle\Tests\Validators;

use \NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Entity\ReferenceLab;
use \NS\SentinelBundle\Entity\User;
use \NS\SentinelBundle\Form\Types\Role;
use \NS\SentinelBundle\Validators\UserAcl;
use \NS\SentinelBundle\Validators\UserAclValidator;

/**
 * Description of UserAclValidatorTest
 *
 * @author gnat
 */
class UserAclValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testHasACLButNotReferenceLab()
    {
        list($constraint, $context, $builder, $validator) = $this->getValidator();
        $acl = new ACL();
        $acl->setType(new Role(Role::NL_LAB));
        $user = new User();
        $user->addAcl($acl);

        $this->assertFalse(in_array('ROLE_RRL_LAB', $user->getRoles()));

        $builder->expects($this->never())
            ->method('buildViolation');

        $validator->initialize($context);
        $validator->validate($user, $constraint);
    }

    public function testHasRRLACLButNoReferenceLab()
    {
        list($constraint, $context, $builder, $validator) = $this->getValidator();
        $acl = new ACL();
        $acl->setType(new Role(Role::RRL_LAB));
        $user = new User();
        $user->addAcl($acl);

        $this->assertTrue(in_array('ROLE_RRL_LAB', $user->getRoles()));

        $context->expects($this->once())
            ->method('buildViolation')
            ->with('The user is designated as able to create reference lab records but no reference lab has been linked')
            ->willReturn($builder);

        $builder->expects($this->once())
            ->method('atPath')
            ->with('referenceLab')
            ->willReturn($builder);
        $builder->expects($this->once())
            ->method('addViolation')
            ->willReturn($builder);

        $validator->initialize($context);
        $validator->validate($user, $constraint);
    }

    /**
     * @param User $user
     * @param bool $expected
     * @dataProvider getAdminUserProvider
     * @group regionUser
     */
    public function testOnlyRegionalUsersCanBeAdmins($user, $expected)
    {
        list($constraint, $context, $builder, $validator) = $this->getValidator();

        if ($expected) {
            $context
                ->expects($this->once())
                ->method('buildViolation')
                ->with('Only users with regional or no roles are allowed to be administrators')
                ->willReturn($builder);

            $builder
                ->expects($this->once())
                ->method('atPath')
                ->with('admin')
                ->willReturn($builder);

            $builder
                ->expects($this->once())
                ->method('addViolation')
                ->willReturn($builder);
        } else {
            $context
                ->expects($this->never())
                ->method('buildViolation');
        }

        $validator->initialize($context);
        $validator->validate($user, $constraint);
    }

    public function getAdminUserProvider()
    {
        $adminUser = new User();
        $adminUser->setAdmin(true);

        $params = array(array($adminUser, false));

        $role = new Role();
        foreach (array_keys($role->getValues()) as $roleType) {
            $acl = new ACL();
            $acl->setType(new Role($roleType));

            $user = new User();
            $user->setReferenceLab(new ReferenceLab());
            $user->setAdmin(true);
            $user->addAcl($acl);

            $params[] = array($user, $roleType !== Role::REGION);
        }

        return $params;
    }

    public function getValidator()
    {
        $constraint = new UserAcl();

        $context = $this->getMockBuilder('\Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $builder = $this->getMockBuilder('\Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $validator = new UserAclValidator();

        return array($constraint, $context, $builder, $validator);
    }
}
