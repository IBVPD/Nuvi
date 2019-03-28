<?php

namespace NS\SentinelBundle\Tests\Validators;

use DateTime;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Form\IBD\Types\PCVType;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Validators\PCV;
use NS\SentinelBundle\Validators\PCVValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class PCVValidatorTest extends TestCase
{
    /** @var AuthorizationCheckerInterface|MockObject */
    private $authChecker;

    /** @var ExecutionContextInterface|MockObject */
    private $context;

    /** @var PCVValidator */
    private $validator;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->authChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->disableOriginalConstructor()->getMock();
        $this->context = $this->getMockBuilder(ExecutionContextInterface::class)->disableOriginalConstructor()->getMock();
        $this->validator = new PCVValidator($this->authChecker);
        $this->validator->initialize($this->context);
    }

    public function testNonPaho(): void
    {
        $this->authChecker->expects($this->once())->method('isGranted')->willReturn(false);
        $ibd = $this->getMockBuilder(IBD::class)->disableOriginalConstructor()->getMock();
        $ibd->expects($this->never())->method('getPcvReceived');
        $this->context->expects($this->never())->method('buildViolation');

        $this->validator->validate($ibd, new PCV());
    }

    public function testPahoPCVReceivedNotSelected(): void
    {
        $this->authChecker->method('isGranted')->willReturn(true);
        $ibd = new IBD();
        $this->context->expects($this->never())->method('buildViolation');
        $this->validator->validate($ibd, new PCV());

        $ibd->setPcvReceived(new VaccinationReceived());
        $this->validator->validate($ibd, new PCV());
    }

    /**
     * @param $received
     *
     * @dataProvider getVaccinationReceived
     */
    public function testValid($received): void
    {
        $this->authChecker->expects($this->once())->method('isGranted')->willReturn(true);
        $ibd = new IBD();
        $ibd->setPcvReceived(new VaccinationReceived($received));
        $ibd->setPcvDoses(new FourDoses(FourDoses::FOUR));
        $ibd->setPcvType(new PCVType(PCVType::PCV13));
        $ibd->setPcvMostRecentDose(new DateTime());

        $this->context->expects($this->never())->method('buildViolation');
        $this->validator->validate($ibd, new PCV());
    }

    public function getVaccinationReceived(): array
    {
        return [
            [VaccinationReceived::YES_HISTORY],
            [VaccinationReceived::YES_CARD],
        ];
    }

    public function testInvalidPcvDoses(): void
    {
        $ibd = new IBD();
        $ibd->setPcvReceived(new VaccinationReceived(VaccinationReceived::YES_CARD));
        $ibd->setPcvType(new PCVType(PCVType::PCV13));
        $ibd->setPcvMostRecentDose(new DateTime());
        $this->validator->validate($ibd, new PCV());
        $this->validator->validate($ibd, $this->expectViolation('pcvDoses'));
    }

    public function testInvalidPcvType(): void
    {
        $ibd = new IBD();
        $ibd->setPcvReceived(new VaccinationReceived(VaccinationReceived::YES_CARD));
        $ibd->setPcvDoses(new FourDoses(FourDoses::FOUR));
        $ibd->setPcvMostRecentDose(new DateTime());
        $this->validator->validate($ibd, $this->expectViolation('pcvType'));
    }

    public function testNonSelectedPcvDoses(): void
    {
        $ibd = new IBD();
        $ibd->setPcvReceived(new VaccinationReceived(VaccinationReceived::YES_CARD));
        $ibd->setPcvDoses(new FourDoses());
        $ibd->setPcvType(new PCVType(PCVType::PCV13));
        $ibd->setPcvMostRecentDose(new DateTime());
        $this->validator->validate($ibd, new PCV());
        $this->validator->validate($ibd, $this->expectViolation('pcvDoses'));
    }

    public function testNonSelectedPcvType(): void
    {
        $ibd = new IBD();
        $ibd->setPcvReceived(new VaccinationReceived(VaccinationReceived::YES_CARD));
        $ibd->setPcvDoses(new FourDoses(FourDoses::FOUR));
        $ibd->setPcvType(new PCVType());
        $ibd->setPcvMostRecentDose(new DateTime());
        $this->validator->validate($ibd, $this->expectViolation('pcvType'));
    }

    public function testInvalidPcvMostRecentDose(): void
    {
        $ibd = new IBD();
        $ibd->setPcvReceived(new VaccinationReceived(VaccinationReceived::YES_CARD));
        $ibd->setPcvDoses(new FourDoses(FourDoses::FOUR));
        $ibd->setPcvType(new PCVType(PCVType::PCV13));
        $ibd->setPcvMostRecentDose();
        $this->validator->validate($ibd, $this->expectViolation('pcvMostRecentDose'));
    }

    private function expectViolation($atPath): PCV
    {
        $constraint = new PCV();
        $this->authChecker->expects($this->once())->method('isGranted')->willReturn(true);
        $builder = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->disableOriginalConstructor()->getMock();
        $this->context->expects($this->once())->method('buildViolation')->with($constraint->message)->willReturn($builder);
        $builder->expects($this->once())->method('atPath')->with($atPath)->willReturnSelf();
        $builder->expects($this->once())->method('addViolation');
        return $constraint;
    }
}
