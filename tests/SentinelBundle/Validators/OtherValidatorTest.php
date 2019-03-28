<?php

namespace NS\SentinelBundle\Tests\Validators;

use InvalidArgumentException;
use NS\SentinelBundle\Entity\IBD\SiteLab;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\Other;
use NS\SentinelBundle\Validators\OtherValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * Description of OtherValidatorTest
 *
 * @author gnat
 */
class OtherValidatorTest extends TestCase
{
    /** @var ExecutionContextInterface|MockObject */
    private $context;

    /** @var OtherValidator|MockObject */
    private $validator;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new OtherValidator();
        $this->validator->initialize($this->context);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateInvalidArgument(): void
    {
        $constraint = new Other([
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'unknownField',
            'otherField' => 'unknownOtherField']);

        $siteLab   = new SiteLab();
        $this->validator->validate($siteLab, $constraint);
    }

    /**
     * @dataProvider getNoViolationFields
     * @param $field
     * @param $otherField
     */
    public function testValidateNoViolation($field, $otherField): void
    {
        $constraint = new Other([
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'csfCultDone',
            'otherField' => 'csfCultResult']);


        $this->context
            ->expects($this->never())
            ->method('buildViolation')
            ->with($constraint->message);

        $siteLab   = new SiteLab();
        $siteLab->setCsfCultDone(new TripleChoice($field));
        $siteLab->setCsfCultResult(new CultureResult($otherField));
        $this->validator->validate($siteLab, $constraint);
    }

    public function getNoViolationFields(): array
    {
        return [
            [TripleChoice::NO, null],
            [TripleChoice::UNKNOWN, null],
            [null, null],
            [TripleChoice::YES, CultureResult::NEGATIVE],
            [TripleChoice::YES, CultureResult::SPN],
            [TripleChoice::YES, CultureResult::HI],
            [TripleChoice::YES, CultureResult::NM],
            [TripleChoice::YES, CultureResult::OTHER],
            [TripleChoice::YES, CultureResult::CONTAMINANT],
            [TripleChoice::YES, CultureResult::UNKNOWN],
        ];
    }

    /**
     * @dataProvider getViolationFields
     * @param $field
     * @param $otherField
     */
    public function testValidateWithViolation($field, $otherField): void
    {
        $constraint = new Other([
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'csfCultDone',
            'otherField' => 'csfCultResult']);

        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder
            ->expects($this->once())
            ->method('addViolation');

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($builder);

        $siteLab = new SiteLab();

        if ($field) {
            $siteLab->setCsfCultDone(new TripleChoice($field));
        }

        if ($otherField) {
            $siteLab->setCsfCultResult(new CultureResult($otherField));
        }

        $this->validator->validate($siteLab, $constraint);
    }

    public function getViolationFields(): array
    {
        return [
            [TripleChoice::YES, null],
            [TripleChoice::YES, CultureResult::NO_SELECTION],
        ];
    }
}
