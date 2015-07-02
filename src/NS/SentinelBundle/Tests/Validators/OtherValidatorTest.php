<?php

namespace NS\SentinelBundle\Tests\Validators;

use \NS\SentinelBundle\Entity\IBD\SiteLab;
use \NS\SentinelBundle\Form\Types\CultureResult;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Validators\Other;
use \NS\SentinelBundle\Validators\OtherValidator;

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
        $constraint = new Other(array(
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'unknownField',
            'otherField' => 'unknownOtherField'));

        $siteLab   = new SiteLab();
        $validator = new OtherValidator();
        $validator->validate($siteLab, $constraint);
    }

    /**
     * @dataProvider getNoViolationFields
     */
    public function testValidateNoViolation($field, $otherField)
    {
        $constraint = new Other(array(
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'csfCultDone',
            'otherField' => 'csfCultResult'));

        $context = $this->getMockBuilder('\Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

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
        return array(
            array(TripleChoice::NO, null),
            array(TripleChoice::UNKNOWN, null),
            array(null, null),
            array(TripleChoice::YES, CultureResult::NEGATIVE),
            array(TripleChoice::YES, CultureResult::SPN),
            array(TripleChoice::YES, CultureResult::HI),
            array(TripleChoice::YES, CultureResult::NM),
            array(TripleChoice::YES, CultureResult::OTHER),
            array(TripleChoice::YES, CultureResult::CONTAMINANT),
            array(TripleChoice::YES, CultureResult::UNKNOWN),
        );
    }

    /**
     * @dataProvider getViolationFields
     */
    public function testValidateWithViolation($field, $otherField)
    {
        $constraint = new Other(array(
            'value'      => '\NS\SentinelBundle\Form\Types\TripleChoice::YES',
            'field'      => 'csfCultDone',
            'otherField' => 'csfCultResult'));

        $context = $this->getMockBuilder('\Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $context->expects($this->once())
            ->method('addViolation')
            ->with($constraint->message);

        $siteLab   = new SiteLab();

        if($field){
            $siteLab->setCsfCultDone(new TripleChoice($field));
        }

        if($otherField) {
            $siteLab->setCsfCultResult(new CultureResult($otherField));
        }

        $validator = new OtherValidator();
        $validator->initialize($context);
        $validator->validate($siteLab, $constraint);
    }

    public function getViolationFields()
    {
        return array(
            array(TripleChoice::YES, null),
            array(TripleChoice::YES, CultureResult::NO_SELECTION),
        );
    }
}