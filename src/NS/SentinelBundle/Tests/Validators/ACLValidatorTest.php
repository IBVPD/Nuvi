<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 05/05/16
 * Time: 11:20 AM
 */

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Form\Types\Role;
use NS\SentinelBundle\Validators\ACLValidator;
use NS\SentinelBundle\Validators\ACL as ACLConstraint;

class ACLValidatorTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @param ACL $acl
     * @dataProvider getNonDeprecatedRoles
     */
    public function testNoDeprecatedRole(ACL $acl)
    {
        $context = $this->getMockBuilder('Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new ACLConstraint();
        $validator = new ACLValidator();
        $validator->initialize($context);

        $validator->validate($acl, $constraint);
    }

    public function getNonDeprecatedRoles()
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

    /**
     * @param ACL $acl
     * @param $firstParameter
     * @param $secondParameter
     *
     * @dataProvider getDeprecatedRoles
     */
    public function testDeprecatedRoles(ACL $acl, $firstParameter, $secondParameter)
    {
        $constraint = new ACLConstraint();

        $builder = $this->getMockBuilder('Symfony\Component\Validator\Context\ConstraintViolationBuilderInterface')
            ->setMethods(['setParameter','atPath','addViolation'])
            ->disableOriginalConstructor()
            ->getMock();

        $builder->expects($this->at(0))
            ->method('setParameter')
            ->with('%type%',$firstParameter)
            ->willReturnSelf();

        $builder->expects($this->at(1))
            ->method('setParameter')
            ->with('%option%',$secondParameter)
            ->willReturnSelf();

        $builder->expects($this->once())
            ->method('atPath')
            ->with('type')
            ->willReturnSelf();

        $builder->expects($this->once())
            ->method('addViolation');

        $context = $this->getMockBuilder('Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $context->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($builder);

        $validator = new ACLValidator();
        $validator->initialize($context);

        $validator->validate($acl, $constraint);
    }

    public function getDeprecatedRoles()
    {
        $aclRegionApi  = new ACL();
        $aclRegionApi->setType(new Role(Role::REGION_API));

        $aclCountryApi = new ACL();
        $aclCountryApi->setType(new Role(Role::COUNTRY_API));

        $aclSiteApi = new ACL();
        $aclSiteApi->setType(new Role(Role::SITE_API));

        $aclRegionImport = new ACL();
        $aclRegionImport->setType(new Role(Role::REGION_IMPORT));

        $aclCountryImport = new ACL();
        $aclCountryImport->setType(new Role(Role::COUNTRY_IMPORT));

        $aclSiteImport = new ACL();
        $aclSiteImport->setType(new Role(Role::SITE_IMPORT));

        return [
            [$aclRegionApi,'REGION','Api Access'],
            [$aclRegionImport,'REGION','Import Access'],
            [$aclCountryApi,'COUNTRY','Api Access'],
            [$aclCountryImport,'COUNTRY','Import Access'],
            [$aclSiteApi,'SITE','Api Access'],
            [$aclSiteImport,'SITE','Import Access'],
        ];
    }
}
