<?php

namespace NS\SentinelBundle\Validators;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class DoneValidator
 * @package NS\SentinelBundle\Validators
 */
class DoneValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        list($field, $doneField) = $this->getFields(is_array($value), $constraint);

        $accessor = new PropertyAccessor();
        $doneFieldValue = $accessor->getValue($value, $doneField);

        if ($doneFieldValue instanceof TripleChoice) {
            return;
        }

        $resultFieldValue = $accessor->getValue($value, $field);

        if (!$resultFieldValue) {
            return;
        }

        // We have a choice field with a selected value
        if ($resultFieldValue instanceof ArrayChoice && !$resultFieldValue->equal(ArrayChoice::NO_SELECTION)) {
            if ($doneFieldValue === null || empty($doneFieldValue)) {
                $this->context
                    ->buildViolation(sprintf('%s has a value but %s is not marked as having been done', $field, $doneField))
                    ->atPath($constraint->tripleChoiceField)
                    ->addViolation();
            } elseif (!$doneFieldValue instanceof ArrayChoice) {
                throw new \InvalidArgumentException(sprintf('Expected ArrayChoice. Field %s is of type %s', $doneField, gettype($doneFieldValue)));
            }
        }
    }

    /**
     * @param $isArray
     * @param Constraint $constraint
     * @return array
     */
    public function getFields($isArray, Constraint $constraint)
    {
        return ($isArray) ?
            array(sprintf('[%s]', $constraint->resultField), sprintf('[%s]', $constraint->tripleChoiceField)) :
            array($constraint->resultField, $constraint->tripleChoiceField);
    }
}
