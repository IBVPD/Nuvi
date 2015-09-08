<?php

namespace NS\SentinelBundle\Validators;

use \Symfony\Component\Validator\Constraint;
use \Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of UserAclValidator
 *
 * @author gnat
 */
class UserAclValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($value, Constraint $constraint)
    {
        if (count($value->getAcls()) == 0) {
            if ($value->getCanCreateCases()) {
                $this->context->buildViolation('The user is designated as able to create cases but has no roles')->atPath('canCreateCases')->addViolation();
            }

            if ($value->getCanCreateLabs()) {
                $this->context->buildViolation('The user is designated as able to create labs but has no roles')->atPath('canCreateLabs')->addViolation();
            }

            if ($value->getCanCreateNLLabs()) {
                $this->context->buildViolation('The user is designated as able to create national lab records but has no roles')->atPath('canCreateNLLabs')->addViolation();
            }

            if ($value->getCanCreateRRLLabs()) {
                $this->context->buildViolation('The user is designated as able to create reference lab records but has no roles')->atPath('canCreateRRLLabs')->addViolation();
            }
        }

        if (($value->getCanCreateRRLLabs() || in_array('ROLE_RRL_LAB', $value->getRoles())) && !$value->hasReferenceLab()) {
            $this->context->buildViolation('The user is designated as able to create reference lab records but no reference lab has been linked')->atPath('referenceLab')->addViolation();
        }
    }
}