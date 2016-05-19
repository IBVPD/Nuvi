<?php

namespace NS\SentinelBundle\Validators;

use \Symfony\Component\Validator\Constraint;
use \Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of OtherValidator
 *
 * @author gnat
 */
class AllOtherValidator extends ConstraintValidator
{
    /**
     * @param object $value
     * @param AllOther|Constraint $constraintInput
     */
    public function validate($value, Constraint $constraintInput)
    {
        if (null === $value) {
            return;
        }

        $group = $this->context->getGroup();

        foreach ($constraintInput->constraints as $constraint) {
            $this->context->validateValue($value, $constraint, $constraint->field, $group);
        }
    }
}
