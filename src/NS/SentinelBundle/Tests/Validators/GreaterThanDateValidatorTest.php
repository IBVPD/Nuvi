<?php

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Validators\GreaterThanDate;
use NS\SentinelBundle\Validators\GreaterThanDateValidator;

class GreaterThanDateValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getIbd
     * @param $ibd
     */
    public function testNotDates($ibd)
    {
        $context = $this->getMockBuilder('Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $context->expects($this->never())
            ->method('buildViolation');

        $validator = new GreaterThanDateValidator();
        $validator->initialize($context);

        $constraint = new GreaterThanDate(array('lessThanField'=>'birthdate','greaterThanField' => 'admDate'));
        $validator->validate($ibd,$constraint);
    }

    public function getIbd()
    {
        $ibdOne = new IBD();
        $ibdTwo = new IBD();
        $ibdTwo->setDob(new \DateTime());

        $ibdThree = new IBD();
        $ibdThree->setAdmDate(new \DateTime());
        return array(
            array($ibdOne),
            array($ibdTwo),
            array($ibdThree),
        );
    }

    public function testValidDates()
    {
        $context = $this->getMockBuilder('Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $context->expects($this->never())
            ->method('buildViolation');

        $validator = new GreaterThanDateValidator();
        $validator->initialize($context);

        $constraint = new GreaterThanDate(array('lessThanField'=>'birthdate','greaterThanField' => 'admDate'));

        $ibd = new IBD();
        $ibd->setDob(new \DateTime('2015-12-28'));
        $ibd->setAdmDate(new \DateTime('2016-07-15'));

        $validator->validate($ibd,$constraint);
    }

    public function testInvalidDates()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Validator\Violation\ConstraintViolationBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $builder->expects($this->once())
            ->method('addViolation');

        $context = $this->getMockBuilder('Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $context->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $validator = new GreaterThanDateValidator();
        $validator->initialize($context);

        $constraint = new GreaterThanDate(array('lessThanField'=>'birthdate','greaterThanField' => 'admDate'));

        $ibd = new IBD();
        $ibd->setDob(new \DateTime('2016-07-15'));
        $ibd->setAdmDate(new \DateTime('2015-12-27'));

        $validator->validate($ibd,$constraint);
    }
}
