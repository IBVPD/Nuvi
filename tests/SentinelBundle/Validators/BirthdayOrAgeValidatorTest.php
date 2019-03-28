<?php

namespace NS\SentinelBundle\Tests\Validators;

use DateTime;
use InvalidArgumentException;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Entity\ValueObjects\YearMonth;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\BirthdayOrAge;
use NS\SentinelBundle\Validators\BirthdayOrAgeValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class BirthdayOrAgeValidatorTest extends TestCase
{
    private $context;

    private $validator;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);

        $this->validator = new BirthdayOrAgeValidator();
        $this->validator->initialize($this->context);
    }

    public function testValidateNonObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected object got string instead');

        $this->validator->validate('some string', new BirthdayOrAge());
    }

    public function testValidateWrongObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected object of type NS\SentinelBundle\Entity\BaseCase got NS\SentinelBundle\Entity\Country instead');

        $this->validator->validate(new Country(), new BirthdayOrAge());
    }

    /**
     * @param BaseCase $object
     * @param bool $expected
     *
     * @dataProvider getValidObjects
     */
    public function testValidObject(BaseCase $object, $expected): void
    {
        if ($expected) {
            $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

            $this->context->expects($this->once())
                ->method('buildViolation')
                ->willReturn($builder);
            
            $builder->expects($this->once())
                ->method('atPath')
                ->with('dobKnown')
                ->willReturn($builder);

            $builder->expects($this->once())
                ->method('addViolation');

        } else {
            $this->context->expects($this->never())->method('buildViolation');
        }

        $constraint = new BirthdayOrAge();

        $this->validator->validate($object, $constraint);
    }

    public function getValidObjects(): array
    {
        $ibd = new IBD();

        $ibdOne = clone $ibd;
        $ibdOne->setBirthdate(new DateTime());

        $ibdTwo = clone $ibd;
        $ibdTwo->setDobYearMonths(new YearMonth(3,6));

        $rv = new RotaVirus();
        $rvOne = clone $rv;
        $rvOne->setBirthdate(new DateTime());

        $rvTwo = clone $rv;
        $rvTwo->setDobYearMonths(new YearMonth(2,9));

        return [
            [$ibd, true],
            [$rv, true],
            [$ibdOne, false],
            [$ibdTwo, false],
            [$rvOne, false],
            [$rvTwo, false],
        ];
    }

    /**
     * @param BaseCase $object
     * @param boolean $birthDateExpected
     * @param boolean $dobYearsExpected
     *
     * @dataProvider getObjects
     */
    public function testAgeIsLessThan5Years(BaseCase $object, $birthDateExpected, $dobYearsExpected): void
    {
        if ($birthDateExpected) {
            $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

            $this->context->expects($this->once())
                ->method('buildViolation')
                ->willReturn($builder);

            $builder->expects($this->once())
                ->method('atPath')
                ->with('birthdate')
                ->willReturn($builder);

            $builder->expects($this->once())
                ->method('addViolation');

        } elseif ($dobYearsExpected) {
            $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

            $this->context->expects($this->once())
                ->method('buildViolation')
                ->willReturn($builder);

            $builder->expects($this->once())
                ->method('atPath')
                ->with('dobKnown')
                ->willReturn($builder);

            $builder->expects($this->once())
                ->method('addViolation');
        } else {
            $this->context->expects($this->never())->method('buildViolation');
        }

        $constraint = new BirthdayOrAge();

        $this->validator->validate($object, $constraint);
    }

    public function getObjects(): array
    {
        $ibdOne = new IBD();
        $ibdOne->setBirthdate(new DateTime('2016-07-12'));
        $ibdOne->setAdmDate(new DateTime('2016-01-31'));

        $ibdTwo = clone $ibdOne;
        $ibdTwo->setBirthdate(new DateTime('2010-01-01'));

        $ibdThree = new IBD();
        $ibdThree->setDobKnown(new TripleChoice(TripleChoice::NO));
        $ibdThree->setDobYearMonths(new YearMonth(5,0));

        $ibdFour = new IBD();
        $ibdFour->setDobKnown(new TripleChoice(TripleChoice::NO));
        $ibdFour->setDobYearMonths(new YearMonth(4,11));

        return [
            [$ibdOne, false, false],
            [$ibdTwo, true, false],
            [$ibdThree, false, true],
            [$ibdFour, false, false],
        ];
    }
}
