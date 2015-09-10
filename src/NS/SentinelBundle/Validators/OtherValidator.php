<?php

namespace NS\SentinelBundle\Validators;

use \Symfony\Component\Validator\Constraint;
use \Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of OtherValidator
 *
 * @author gnat
 */
class OtherValidator extends ConstraintValidator
{
    /**
     * @param object $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $fMethod      = sprintf("get%s", $constraint->field);
        $otherFMethod = sprintf("get%s", $constraint->otherField);

        if (!method_exists($value, $fMethod) || !method_exists($value, $otherFMethod)) {
            throw new \InvalidArgumentException(sprintf("Either %s or %s doesn't exist for object %s", $constraint->field, $constraint->otherField, get_class($value)));
        }

        $const = constant($constraint->value);

        if ($value->$fMethod() && $value->$fMethod()->equal($const) && ($value->$otherFMethod() === null || $value->$otherFMethod() == '')) {
            $this->context->addViolation($constraint->message);
        }
    }
}
