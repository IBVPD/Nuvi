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
     * @param $user
     * @param Constraint $constraint
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($user, Constraint $constraint)
    {
        if (in_array('ROLE_RRL_LAB', $user->getRoles()) && !$user->hasReferenceLab()) {
            $this->context->buildViolation('The user is designated as able to create reference lab records but no reference lab has been linked')->atPath('referenceLab')->addViolation();
        }
    }
}
