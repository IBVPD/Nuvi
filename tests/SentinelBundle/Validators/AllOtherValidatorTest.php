<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 16/01/17
 * Time: 4:48 PM
 */

namespace NS\SentinelBundle\Tests\Validators;


use NS\SentinelBundle\Entity\IBD\SiteLab;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\AllOther;
use NS\SentinelBundle\Validators\AllOtherValidator;
use NS\SentinelBundle\Validators\Other;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AllOtherValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValueIsNullIsNull()
    {
        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->never())->method('getGroup');

        $validator = new AllOtherValidator();
        $validator->initialize($context);

        $this->assertNull($validator->validate(null, new AllOther(['constraints' => []])));
    }

    public function testValidateOther()
    {
        $constraints = [
            new Other(['field'=>'csfCultDone','value'=>'\NS\SentinelBundle\Form\Types\TripleChoice::YES','otherField'=>'csfCultResult','message'=>'cult done']),
            new Other(['field'=>'csfCultResult','value'=>'\NS\SentinelBundle\Form\Types\CultureResult::OTHER','otherField'=>'csfCultOther','message'=>'cult result']),
        ];

        $mockValidator = $this->createMock(ValidatorInterface::class);
        $mockValidator
            ->expects($this->atLeast(2))
            ->method('validate')
            ->willReturn(null);

        $context = $this->createMock(ExecutionContextInterface::class);
        $context
            ->expects($this->once())
            ->method('getGroup')
            ->willReturn(['Default']);

        $context
            ->expects($this->once())
            ->method('getValidator')
            ->willReturn($mockValidator);

        $recurseValidator = $this->createMock(ContextualValidatorInterface::class);
        $recurseValidator
            ->expects($this->at(0))
            ->method('atPath')
            ->with('csfCultDone')
            ->willReturn($mockValidator);
        $recurseValidator
            ->expects($this->at(1))
            ->method('atPath')
            ->with('csfCultResult')
            ->willReturn($mockValidator);

        $mockValidator->expects($this->any())
            ->method('inContext')
            ->with($context)
            ->willReturn($recurseValidator);

        $validator = new AllOtherValidator();
        $validator->initialize($context);

        $lab = new SiteLab();
        $lab->setCsfCultDone(new TripleChoice(TripleChoice::YES));

        $violationList = $validator->validate($lab, new AllOther(['constraints' => $constraints]));
        $this->assertNull($violationList);
    }
}
