<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 18/05/16
 * Time: 4:00 PM
 */

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Validators\BirthdayOrAge;
use NS\SentinelBundle\Validators\BirthdayOrAgeValidator;

class BirthdayOrAgeValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateNonObject()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Expected object got string instead');

        $validator = new BirthdayOrAgeValidator();
        $validator->validate('some string', new BirthdayOrAge());
    }

    public function testValidateWrongObject()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Expected object of type NS\SentinelBundle\Entity\BaseCase got NS\SentinelBundle\Entity\Country instead');

        $validator = new BirthdayOrAgeValidator();
        $validator->validate(new Country(), new BirthdayOrAge());
    }

    /**
     * @param BaseCase $object
     * @param bool $expected
     *
     * @dataProvider getValidObjects
     */
    public function testValidObject($object, $expected)
    {
        $context = $this->createMock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        if($expected) {
            $builder = $this->createMock('Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface');

            $context->expects($this->once())
                ->method('buildViolation')
                ->willReturn($builder);
            
            $builder->expects($this->once())
                ->method('atPath')
                ->with('dobKnown')
                ->willReturn($builder);

            $builder->expects($this->once())
                ->method('addViolation');

        } else {
            $context->expects($this->never())->method('addViolation');
        }

        $constraint = new BirthdayOrAge();
        $validator  = new BirthdayOrAgeValidator();
        $validator->initialize($context);

        $validator->validate($object, $constraint);
    }

    public function getValidObjects()
    {
        $ibd = new IBD();

        $ibdOne = clone $ibd;
        $ibdOne->setBirthdate(new \DateTime());

        $ibdTwo = clone $ibd;
        $ibdTwo->setDobYears(3);
        $ibdTwo->setDobMonths(6);

        $rv = new RotaVirus();
        $rvOne = clone $rv;
        $rvOne->setBirthdate(new \DateTime());

        $rvTwo = clone $rv;
        $rvTwo->setDobYears(2);
        $rvTwo->setDobMonths(9);

        return array(
            array($ibd, true),
            array($rv, true),
            array($ibdOne, false),
            array($ibdTwo, false),
            array($rvOne, false),
            array($rvTwo, false),
        );
    }
}
