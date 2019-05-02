<?php

namespace NS\SentinelBundle\Validators;

use InvalidArgumentException;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OtherValidator extends ConstraintValidator
{
    /** @var PropertyAccessor */
    private $propertyAccessor;

    /**
     * @param object           $value
     * @param Other|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        $fieldValue = $this->propertyAccessor->getValue($value,$constraint->field);
        if ($fieldValue instanceof ArrayChoice) {
            $otherFieldValue        = $this->propertyAccessor->getValue($value, $constraint->otherField);
            $otherFieldValueIsEmpty = ($otherFieldValue === null || $otherFieldValue == '');

            foreach ($constraint->value as $constVar) {
                $const = constant($constVar);
                if ($otherFieldValueIsEmpty && $fieldValue->equal($const)) {
                    $this->context
                        ->buildViolation($constraint->message)
                        ->setParameters([
                            '{{ field }}' => $constraint->field,
                            '{{ otherField }}' => $constraint->otherField,
                        ])
                        ->atPath($constraint->otherField)
                        ->addViolation();
                    break;
                }
            }
        }
    }
}
