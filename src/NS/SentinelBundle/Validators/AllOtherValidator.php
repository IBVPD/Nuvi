<?php

namespace NS\SentinelBundle\Validators;

use \Symfony\Component\Validator\Constraint;
use \Symfony\Component\Validator\ConstraintValidator;
use \Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Description of OtherValidator
 *
 * @author gnat
 */
class AllOtherValidator extends ConstraintValidator
{
    /**
     * @param type $value
     * @param Constraint $constraintInput
     */
    public function validate($value, Constraint $constraintInput)
    {
        if (null === $value)
            return;

        $group = $this->context->getGroup();

        foreach ($constraintInput->constraints as $key => $constraint)
            $this->context->validateValue($value, $constraint, $constraint->field, $group);
    }
}
