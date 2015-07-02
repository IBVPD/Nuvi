<?php

namespace NS\SentinelBundle\Tests\Validators;

use \NS\SentinelBundle\Entity\ACL;
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
        list($constraint, $context,$builder,$validator) = $this->getValidator();
        $acl = new ACL();
        $acl->setType(new Role(Role::NL_LAB));
        $user = new User();
        $user->addAcl($acl);

        $this->assertFalse(in_array('ROLE_RRL_LAB', $user->getRoles()));

        $builder->expects($this->never())
            ->method('buildViolation');

        $validator->initialize($context);
        $validator->validate($user,$constraint);
    }

    public function testHasRRLACLButNoReferenceLab()
    {
        list($constraint, $context,$builder,$validator) = $this->getValidator();
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
        $validator->validate($user,$constraint);
    }

    public function testNoAclWithRRLOverride()
    {
        list($constraint, $context,$builder,$validator) = $this->getValidator();

        $context->expects($this->at(0))
            ->method('buildViolation')
            ->with('The user is designated as able to create reference lab records but has no roles')
            ->willReturn($builder);

        $context->expects($this->at(1))
            ->method('buildViolation')
            ->with('The user is designated as able to create reference lab records but no reference lab has been linked')
            ->willReturn($builder);

        $builder->expects($this->any())
            ->method('atPath')
//            ->with('canCreateRRLLabs')
            ->willReturn($builder);

//        $builder->expects($this->at(0))
//            ->method('atPath')
//            ->with('referenceLab')
//            ->willReturn($builder);

        $builder->expects($this->any())
            ->method('addViolation');

        $user = new User();
        $user->setCanCreateRRLLabs(true);

        $validator->initialize($context);
        $validator->validate($user, $constraint);
    }

    /**
     * @param string $userMethod
     * @param string $message
     * @param string $path
     * @dataProvider getNoAclMessages
     */
    public function testNoAclWithCreateOverride($userMethod, $message, $path)
    {
        list($constraint, $context,$builder,$validator) = $this->getValidator();

        $context->expects($this->once())
            ->method('buildViolation')
            ->with($message)
            ->willReturn($builder);

        $builder->expects($this->any())
            ->method('atPath')
            ->with($path)
            ->willReturn($builder);
        $builder->expects($this->any())
            ->method('addViolation');

        $user = new User();
        call_user_func_array(array($user,$userMethod), array(true));
        
        $validator->initialize($context);
        $validator->validate($user, $constraint);
    }

    public function getNoAclMessages()
    {
        return array(
            array('setCanCreateCases', 'The user is designated as able to create cases but has no roles', 'canCreateCases'),
            array('setCanCreateLabs', 'The user is designated as able to create labs but has no roles', 'canCreateLabs'),
            array('setCanCreateNLLabs', 'The user is designated as able to create national lab records but has no roles', 'canCreateNLLabs'),
//            array('setCanCreateRRLLabs', 'The user is designated as able to create reference lab records but has no roles', 'canCreateRRLLabs'),
        );
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

        $validator  = new UserAclValidator();

        return array($constraint, $context,$builder,$validator);
    }
}