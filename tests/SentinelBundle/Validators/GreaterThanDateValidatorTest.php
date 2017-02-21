<?php

namespace NS\SentinelBundle\Tests\Validators;

use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Validators\GreaterThanDate;
use NS\SentinelBundle\Validators\GreaterThanDateValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class GreaterThanDateValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $context;

    /** @var  ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $validator;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new GreaterThanDateValidator($this->context);
        $this->validator->initialize($this->context);
    }

    /**
     * @dataProvider getIbd
     * @param $ibd
     */
    public function testNotDates($ibd)
    {
        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $constraint = new GreaterThanDate(['lessThanField' => 'birthdate', 'greaterThanField' => 'admDate']);
        $this->validator->validate($ibd, $constraint);
    }

    public function getIbd()
    {
        $ibdOne = new IBD();
        $ibdTwo = new IBD();
        $ibdTwo->setDob(new \DateTime());

        $ibdThree = new IBD();
        $ibdThree->setAdmDate(new \DateTime());
        return [
            [$ibdOne],
            [$ibdTwo],
            [$ibdThree],
        ];
    }

    public function testValidDates()
    {
        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $constraint = new GreaterThanDate(['lessThanField' => 'birthdate', 'greaterThanField' => 'admDate']);

        $ibd = new IBD();
        $ibd->setDob(new \DateTime('2015-12-28'));
        $ibd->setAdmDate(new \DateTime('2016-07-15'));

        $this->validator->validate($ibd, $constraint);
    }

    public function testInvalidDates()
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder
            ->expects($this->once())
            ->method('addViolation');

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $constraint = new GreaterThanDate(['lessThanField' => 'birthdate', 'greaterThanField' => 'admDate']);

        $ibd = new IBD();
        $ibd->setDob(new \DateTime('2016-07-15'));
        $ibd->setAdmDate(new \DateTime('2015-12-27'));

        $this->validator->validate($ibd, $constraint);
    }

    public function testFieldNameDifferences()
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder
            ->expects($this->atLeast(2))
            ->method('addViolation');

        $this->context
            ->expects($this->atLeast(2))
            ->method('buildViolation')
            ->willReturn($builder);

        $ibd = new IBD();
        $ibd->setAdmDate(new \DateTime('2016-12-27'));
        $ibd->setPleuralFluidCollectDate(new \DateTime('2015-07-15'));

        $this->validator->validate($ibd, new GreaterThanDate(['lessThanField' => 'admDate', 'greaterThanField' => 'pleural_fluid_collect_date']));
        $this->validator->validate($ibd, new GreaterThanDate(['lessThanField' => 'admDate', 'greaterThanField' => 'pleuralFluidCollectDate']));
    }
}
