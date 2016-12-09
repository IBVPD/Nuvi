<?php

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\Done;
use NS\SentinelBundle\Validators\DoneValidator;

class DoneValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function testNoResultFieldValue()
    {
        $data = array(
            'tripleChoiceField' => '',
            'resultField' => '',
        );

        $context = $this->createMock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(array('resultField'=>'resultField', 'tripleChoiceField'=>'tripleChoiceField'));
        $validator = new DoneValidator();
        $validator->initialize($context);

        $validator->validate($data, $constraint);
    }

    public function testTripleChoiceFieldIsSet()
    {
        $data = array(
            'tripleChoiceField' => new TripleChoice(1),
            'resultField' => new TripleChoice(1),
        );

        $context = $this->createMock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(array('resultField'=>'resultField', 'tripleChoiceField'=>'tripleChoiceField'));
        $validator = new DoneValidator();
        $validator->initialize($context);
        $validator->validate($data, $constraint);
    }

    /**
     * @dataProvider getInvalidStates
     * @param array $data
     */
    public function testEmptyTripleChoiceField(array $data)
    {
        $builder = $this->createMock('Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface');

        $context = $this->createMock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $builder->expects($this->once())
            ->method('atPath')
            ->with('tripleChoiceField')
            ->willReturnSelf();

        $builder->expects($this->once())
            ->method('addViolation');

        $constraint = new Done(array('resultField'=>'resultField', 'tripleChoiceField'=>'tripleChoiceField'));
        $validator = new DoneValidator();
        $validator->initialize($context);
        $validator->validate($data, $constraint);
    }

    public static function getInvalidStates()
    {
        return array(
            array(array(
                'tripleChoiceField' => '',
                'resultField' => new TripleChoice(1),
            )),
            array(array(
                'tripleChoiceField' => null,
                'resultField' => new TripleChoice(1),
            )),
            array(array(
                'resultField' => new TripleChoice(1),
            ))
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotArrayChoice()
    {
        $data = array(
            'resultField' => new TripleChoice(TripleChoice::NO),
            'tripleChoiceField' => new \stdClass(),

        );
        $context = $this->createMock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(array('resultField'=>'resultField', 'tripleChoiceField'=>'tripleChoiceField'));
        $validator = new DoneValidator();
        $validator->initialize($context);
        $validator->validate($data, $constraint);
    }
}
