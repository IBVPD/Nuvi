<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/02/17
 * Time: 4:39 PM
 */

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Validators\NoFutureDate;
use NS\SentinelBundle\Validators\NoFutureDateValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class NoFutureDateValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $context;

    /** @var  ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $validator;

    public function testNotDate()
    {
        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate(null,new NoFutureDate());
        $this->validator->validate('',new NoFutureDate());
        $this->validator->validate(1,new NoFutureDate());
        $this->validator->validate('something',new NoFutureDate());
    }

    public function testPastDate()
    {
        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate(new \DateTime("yesterday"),new NoFutureDate());
    }

    public function testFutureDate()
    {
        $builder = $this->createMock(ConstraintViolationBuilder::class);
        $builder
            ->expects($this->once())
            ->method('addViolation');

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->with('This date is in the future')
            ->willReturn($builder);

        $this->validator->validate(new \DateTime("+1 day"),new NoFutureDate());

    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new NoFutureDateValidator();
        $this->validator->initialize($this->context);
    }


}
