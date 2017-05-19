<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/05/17
 * Time: 10:01 AM
 */

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

    /**
     * PCVValidator constructor.
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @param IBD $value
     * @param Constraint|PCV $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->authChecker->isGranted('ROLE_AMR')) {
            if ($value->getPcvReceived() && ($value->getPcvReceived()->equal(VaccinationReceived::YES_CARD) || $value->getPcvReceived()->equal(VaccinationReceived::YES_HISTORY))) {
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
}
