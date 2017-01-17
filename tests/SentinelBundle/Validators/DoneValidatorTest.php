<?php

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\Done;
use NS\SentinelBundle\Validators\DoneValidator;

class DoneValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function testNoResultFieldValue()
    {
        $data = [
            'tripleChoiceField' => '',
            'resultField' => '',
        ];

        $context = $this->createMock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(['resultField'=>'resultField', 'tripleChoiceField'=>'tripleChoiceField']);
        $validator = new DoneValidator();
        $validator->initialize($context);

        $validator->validate($data, $constraint);
    }

    public function testTripleChoiceFieldIsSet()
    {
        $data = [
            'tripleChoiceField' => new TripleChoice(1),
            'resultField' => new TripleChoice(1),
        ];

        $context = $this->createMock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(['resultField'=>'resultField', 'tripleChoiceField'=>'tripleChoiceField']);
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

        $constraint = new Done(['resultField'=>'resultField', 'tripleChoiceField'=>'tripleChoiceField']);
        $validator = new DoneValidator();
        $validator->initialize($context);
        $validator->validate($data, $constraint);
    }

    public static function getInvalidStates()
    {
        return [
            [[
                'tripleChoiceField' => '',
                'resultField' => new TripleChoice(1),
            ]],
            [[
                'tripleChoiceField' => null,
                'resultField' => new TripleChoice(1),
            ]],
            [[
                'resultField' => new TripleChoice(1),
            ]]
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotArrayChoice()
    {
        $data = [
            'resultField' => new TripleChoice(TripleChoice::NO),
            'tripleChoiceField' => new \stdClass(),

        ];
        $context = $this->createMock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(['resultField'=>'resultField', 'tripleChoiceField'=>'tripleChoiceField']);
        $validator = new DoneValidator();
        $validator->initialize($context);
        $validator->validate($data, $constraint);
    }
}
