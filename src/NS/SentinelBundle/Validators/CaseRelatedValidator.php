<?php

namespace NS\SentinelBundle\Validators;

use InvalidArgumentException;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CaseRelatedValidator extends ConstraintValidator
{
    /**
     * @param mixed                  $value
     * @param CaseRelated|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        $obj = $this->context->getObject();
        if (!is_object($obj) || !method_exists($obj, 'getCaseFile')) {
            throw new InvalidArgumentException('Expected object with getCaseFile method');
        }

        if ($value === null) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            /** @var BaseCase|Pneumonia|Meningitis $case */
            $case = $obj->getCaseFile();
            try {
                $caseFieldValue = $propertyAccessor->getValue($case, $constraint->caseField);
                foreach ((array)$constraint->caseFieldValue as $caseFieldValueTest) {
                    if (($caseFieldValue instanceof ArrayChoice && $caseFieldValue->equal($caseFieldValueTest)) || (is_scalar($caseFieldValue) && $caseFieldValue == $caseFieldValueTest)) {
                        $this->context->buildViolation($constraint->message)->addViolation();
                        return;
                    }
                }

            } catch (NoSuchPropertyException $exception) {
                throw new InvalidArgumentException("Case does not have property {$constraint->caseField}");
            }
        }
    }
}
