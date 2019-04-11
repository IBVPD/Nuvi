<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GreaterThanDateValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed                      $value      The value that should be validated
     * @param GreaterThanDate|Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint): void
    {
        $accessor      = PropertyAccess::createPropertyAccessor();
        $lessThanValue = $greaterThanValue = null;

        try {
            $lessThanValue    = $accessor->getValue($value, $constraint->lessThanField);
            $greaterThanValue = $accessor->getValue($value, $constraint->greaterThanField);
        } catch (UnexpectedTypeException $exception) {

        }

        if ($lessThanValue instanceof \DateTime && $greaterThanValue instanceof \DateTime) {
            if ($lessThanValue > $greaterThanValue) {
                $message = !empty($constraint->message) ? $constraint->message : sprintf('%s: %s is not greater than %s: %s', $constraint->greaterThanField, $greaterThanValue->format('Y-m-d'), $constraint->lessThanField, $lessThanValue->format('Y-m-d'));
                $this->context
                    ->buildViolation($message)
                    ->atPath($constraint->atPath ?? $constraint->greaterThanField)
                    ->addViolation();
            }
        }
    }
}
