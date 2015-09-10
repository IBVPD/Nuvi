<?php

namespace NS\ImportBundle\Entity\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ImportValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($value, Constraint $constraint)
    {
        if($value->getInputDateStart() > $value->getInputDateEnd()) {
            $this->context->buildViolation('The end date cannot be before the start date')->addViolation();
        }
    }
}
