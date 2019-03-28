<?php

namespace NS\SentinelBundle\Tests\Validators;

use InvalidArgumentException;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\Done;
use NS\SentinelBundle\Validators\DoneValidator;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class DoneValidatorTest extends TestCase
{

    public function testNoResultFieldValue(): void
    {
        $data = [
            'tripleChoiceField' => '',
            'resultField' => '',
        ];

        $context = $this->createMock(ExecutionContextInterface::class);

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(['resultField' => 'resultField', 'tripleChoiceField' => 'tripleChoiceField']);
        $validator  = new DoneValidator();
        $validator->initialize($context);

        $validator->validate($data, $constraint);
    }

    public function testTripleChoiceFieldIsSet(): void
    {
        $data = [
            'tripleChoiceField' => new TripleChoice(1),
            'resultField' => new TripleChoice(1),
        ];

        $context = $this->createMock(ExecutionContextInterface::class);

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(['resultField' => 'resultField', 'tripleChoiceField' => 'tripleChoiceField']);
        $validator  = new DoneValidator();
        $validator->initialize($context);
        $validator->validate($data, $constraint);
    }

    /**
     * @dataProvider getInvalidStates
     *
     * @param array $data
     */
    public function testEmptyTripleChoiceField(array $data): void
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $context = $this->createMock(ExecutionContextInterface::class);

        $context->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $builder->expects($this->once())
            ->method('atPath')
            ->with('tripleChoiceField')
            ->willReturnSelf();

        $builder->expects($this->once())
            ->method('addViolation');

        $constraint = new Done(['resultField' => 'resultField', 'tripleChoiceField' => 'tripleChoiceField']);
        $validator  = new DoneValidator();
        $validator->initialize($context);
        $validator->validate($data, $constraint);
    }

    public static function getInvalidStates(): array
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
            ]],
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotArrayChoice(): void
    {
        $data    = [
            'resultField' => new TripleChoice(TripleChoice::NO),
            'tripleChoiceField' => new stdClass(),

        ];
        $context = $this->createMock(ExecutionContextInterface::class);

        $context->expects($this->never())
            ->method('buildViolation');

        $constraint = new Done(['resultField' => 'resultField', 'tripleChoiceField' => 'tripleChoiceField']);
        $validator  = new DoneValidator();
        $validator->initialize($context);
        $validator->validate($data, $constraint);
    }
}
