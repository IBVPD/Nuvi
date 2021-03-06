<?php

namespace NS\SentinelBundle\Tests\Validators;

use DateTime;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Validators\GreaterThanDate;
use NS\SentinelBundle\Validators\GreaterThanDateValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class GreaterThanDateValidatorTest extends TestCase
{
    /** @var ExecutionContextInterface|MockObject */
    private $context;

    /** @var  ValidatorInterface|MockObject */
    private $validator;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new GreaterThanDateValidator();
        $this->validator->initialize($this->context);
    }

    /**
     * @dataProvider getIbd
     * @param $ibd
     */
    public function testNotDates($ibd): void
    {
        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $constraint = new GreaterThanDate(['lessThanField' => 'birthdate', 'greaterThanField' => 'admDate']);
        $this->validator->validate($ibd, $constraint);
    }

    public function getIbd(): array
    {
        $ibdOne = new IBD();
        $ibdTwo = new IBD();
        $ibdTwo->setDob(new DateTime());

        $ibdThree = new IBD();
        $ibdThree->setAdmDate(new DateTime());
        return [
            [$ibdOne],
            [$ibdTwo],
            [$ibdThree],
        ];
    }

    public function testValidDates(): void
    {
        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $constraint = new GreaterThanDate(['lessThanField' => 'birthdate', 'greaterThanField' => 'admDate']);

        $ibd = new IBD();
        $ibd->setDob(new DateTime('2015-12-28'));
        $ibd->setAdmDate(new DateTime('2016-07-15'));

        $this->validator->validate($ibd, $constraint);
    }

    public function testInvalidDates(): void
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder->expects($this->once())
            ->method('atPath')
            ->with('admDate')
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('addViolation');

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $constraint = new GreaterThanDate(['lessThanField' => 'birthdate', 'greaterThanField' => 'admDate']);

        $ibd = new IBD();
        $ibd->setDob(new DateTime('2016-07-15'));
        $ibd->setAdmDate(new DateTime('2015-12-27'));

        $this->validator->validate($ibd, $constraint);
    }

    public function testFieldNameDifferences(): void
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder->expects($this->once())
            ->method('atPath')
            ->with('pleural_fluid_collect_date')
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('addViolation');

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $ibd = new IBD();
        $ibd->setAdmDate(new DateTime('2016-12-27'));
        $ibd->setPleuralFluidCollectDate(new DateTime('2015-07-15'));

        $this->validator->validate($ibd, new GreaterThanDate(['lessThanField' => 'admDate', 'greaterThanField' => 'pleural_fluid_collect_date']));
    }

    public function testNullGraph(): void
    {
        $ibd = new IBD();

        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $accessor = PropertyAccess::createPropertyAccessor();
        try {
            $accessor->getValue($ibd, 'siteLab.csfCultDone.value');
            $this->fail('Accessor needed to throw an UnexpectedTypeException');
        } catch (UnexpectedTypeException $exception) {
            $constraint = new GreaterThanDate(['lessThanField' => 'siteLab.csfCultDone.value', 'greaterThanField' => 'admDate']);
            $this->validator->validate($ibd, $constraint);
        }
    }

    public function testAtPath(): void
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder->expects($this->once())
            ->method('atPath')
            ->with('dob')
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('addViolation');

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $constraint = new GreaterThanDate(['lessThanField' => 'birthdate', 'greaterThanField' => 'admDate','atPath' => 'dob']);

        $ibd = new IBD();
        $ibd->setDob(new DateTime('2016-07-15'));
        $ibd->setAdmDate(new DateTime('2015-12-27'));

        $this->validator->validate($ibd, $constraint);
    }
}
