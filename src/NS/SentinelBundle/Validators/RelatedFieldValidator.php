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
use NS\SentinelBundle\Entity\BaseCase;

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
     * @param BaseCase $obj
     * @param Constraint|RelatedField $constraint
     */
    public function validate($obj, Constraint $constraint)
    {
        $value = $this->propertyAccessor->getValue($obj, $constraint->sourceField);
        foreach ($constraint->sourceValue as $varValue) {
            if ( ($value instanceof ArrayChoice && $value->equal($varValue)) || $value == $varValue ) {
                $this->validateFields($obj,$constraint->fields);
            }
        }
    }

    /**
     * @param BaseCase $obj
     * @param array $fields
     */
    private function validateFields(BaseCase $obj, array $fields)
    {
        foreach($fields as $field) {
            $value = $this->propertyAccessor->getValue($obj,$field);
            if($value === null || empty($value) || ($value instanceof ArrayChoice && $value->equal(ArrayChoice::NO_SELECTION))) {
                $this->context->buildViolation('field-is-required-due-to-adm-diagnosis')->atPath($field)->addViolation();
            }
        }
    }
}
