<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 03/09/15
 * Time: 1:59 PM
 */

namespace NS\ImportBundle\Entity\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ImportValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if($value->getInputDateStart() > $value->getInputDateEnd()) {
            $this->context->buildViolation('The end date cannot be before the start date')->addViolation();
        }
    }
}