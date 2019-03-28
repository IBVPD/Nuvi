<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/02/17
 * Time: 4:39 PM
 */

namespace NS\SentinelBundle\Tests\Validators;

use DateTime;
use NS\SentinelBundle\Validators\NoFutureDate;
use NS\SentinelBundle\Validators\NoFutureDateValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

class NoFutureDateValidatorTest extends TestCase
{
    /** @var ExecutionContextInterface|MockObject */
    private $context;

    /** @var  ValidatorInterface|MockObject */
    private $validator;

    public function testNotDate(): void
    {
        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate(null,new NoFutureDate());
        $this->validator->validate('',new NoFutureDate());
        $this->validator->validate(1,new NoFutureDate());
        $this->validator->validate('something',new NoFutureDate());
    }

    public function testPastDate(): void
    {
        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate(new DateTime('yesterday'),new NoFutureDate());
    }

    public function testFutureDate(): void
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

        $this->validator->validate(new DateTime('+1 day'),new NoFutureDate());

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
