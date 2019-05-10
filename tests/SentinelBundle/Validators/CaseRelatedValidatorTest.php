<?php

namespace NS\SentinelBundle\Tests\Validators;

use InvalidArgumentException;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Meningitis\SiteLab;
use NS\SentinelBundle\Validators\CaseRelated;
use NS\SentinelBundle\Validators\CaseRelatedValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CaseRelatedValidatorTest extends TestCase
{
    /** @var CaseRelatedValidator */
    private $validator;

    /** @var ExecutionContextInterface|MockObject */
    private $context;

    public function setUp()
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new CaseRelatedValidator();
        $this->validator->initialize($this->context);
    }

    /**
     * @param $ret
     * @dataProvider getInvalidObjects
     */
    public function testInvalidObject($ret): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->context->expects($this->once())->method('getObject')->willReturn($ret);
        $this->validator->validate('value', new CaseRelated([]));
    }

    public function getInvalidObjects(): array
    {
        return [
            [[]],
            [new \stdClass()],
        ];
    }

    public function testValueIsNotNull(): void
    {
        $lab = $this->createMock(SiteLab::class);
        $lab->expects($this->never())->method('getCaseFile');

        $this->context->expects($this->once())->method('getObject')->willReturn($lab);
        $this->context->expects($this->never())->method('buildViolation');
        $this->validator->validate('value', new CaseRelated([]));
    }
}
