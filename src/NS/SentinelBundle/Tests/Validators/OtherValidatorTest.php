<?php

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Entity\IBD\SiteLab;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\Other;
use NS\SentinelBundle\Validators\OtherValidator;

/**
 * Description of OtherValidatorTest
 *
 * @author gnat
 */
class OtherValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateInvalidArgument()
    {
        $constraint = new Other([
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'unknownField',
            'otherField' => 'unknownOtherField']);

        $siteLab   = new SiteLab();
        $validator = new OtherValidator();
        $validator->validate($siteLab, $constraint);
    }

    /**
     * @dataProvider getNoViolationFields
     * @param $field
     * @param $otherField
     */
    public function testValidateNoViolation($field, $otherField)
    {
        $constraint = new Other([
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'csfCultDone',
            'otherField' => 'csfCultResult']);

        $context = $this->createMock('\Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->never())
            ->method('addViolation')
            ->with($constraint->message);

        $siteLab   = new SiteLab();
        $siteLab->setCsfCultDone(new TripleChoice($field));
        $siteLab->setCsfCultResult(new CultureResult($otherField));
        $validator = new OtherValidator();
        $validator->initialize($context);
        $validator->validate($siteLab, $constraint);
    }

    public function getNoViolationFields()
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
    public function testValidateWithViolation($field, $otherField)
    {
        $constraint = new Other([
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'csfCultDone',
            'otherField' => 'csfCultResult']);

        $context = $this->createMock('\Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->once())
            ->method('addViolation')
            ->with($constraint->message);

        $siteLab   = new SiteLab();

        if ($field) {
            $siteLab->setCsfCultDone(new TripleChoice($field));
        }

        if ($otherField) {
            $siteLab->setCsfCultResult(new CultureResult($otherField));
        }

        $validator = new OtherValidator();
        $validator->initialize($context);
        $validator->validate($siteLab, $constraint);
    }

    public function getViolationFields()
    {
        return [
            [TripleChoice::YES, null],
            [TripleChoice::YES, CultureResult::NO_SELECTION],
        ];
    }
}
