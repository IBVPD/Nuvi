<?php

namespace NS\SentinelBundle\Validators;

use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PCVValidator extends ConstraintValidator
{
    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @param IBD $value
     * @param Constraint|PCV $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($this->authChecker->isGranted('ROLE_AMR') && $value->getPcvReceived() && ($value->getPcvReceived()->equal(VaccinationReceived::YES_CARD) || $value->getPcvReceived()->equal(VaccinationReceived::YES_HISTORY))) {
            if (!$value->getPcvDoses() || $value->getPcvDoses()->equal(ArrayChoice::NO_SELECTION)) {
                $this->context->buildViolation($constraint->message)->atPath('pcvDoses')->addViolation();
            }

            if (!$value->getPcvType() || $value->getPcvType()->equal(ArrayChoice::NO_SELECTION)) {
                $this->context->buildViolation($constraint->message)->atPath('pcvType')->addViolation();
            }

            if (!$value->getPcvMostRecentDose()) {
                $this->context->buildViolation($constraint->message)->atPath('pcvMostRecentDose')->addViolation();
            }
        }
    }
}
