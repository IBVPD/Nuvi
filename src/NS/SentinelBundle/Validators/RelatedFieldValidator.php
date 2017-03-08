<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 27/02/17
 * Time: 1:54 PM
 */

namespace NS\SentinelBundle\Validators;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RelatedFieldValidator extends ConstraintValidator
{
    /** @var \Symfony\Component\PropertyAccess\PropertyAccessor */
    private $propertyAccessor;

    /**
     * RelatedFieldValidator constructor.
     */
    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param object $obj
     * @param Constraint|RelatedField $constraint
     */
    public function validate($obj, Constraint $constraint)
    {
        $value = $this->propertyAccessor->getValue($obj, $constraint->sourceField);
        foreach ($constraint->sourceValue as $varValue) {
            if (($value instanceof ArrayChoice && $value->equal($varValue)) || $value == $varValue) {
                $this->validateFields($obj, $constraint);
            }
        }
    }

    /**
     * @param object $obj
     * @param RelatedField $constraint
     */
    private function validateFields($obj, RelatedField $constraint)
    {
        foreach ($constraint->fields as $field) {
            $value = $this->propertyAccessor->getValue($obj, $field);
            if ($value === null || empty($value) || ($value instanceof ArrayChoice && $value->equal(ArrayChoice::NO_SELECTION))) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->setParameters(['%source%' => $constraint->sourceField, '%field%' => $field])
                    ->atPath($field)
                    ->addViolation();
            }
        }
    }
}
