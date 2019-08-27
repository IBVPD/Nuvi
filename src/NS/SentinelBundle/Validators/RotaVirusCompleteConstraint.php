<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RotaVirusCompleteConstraint extends Constraint
{
    public function validatedBy(): string
    {
        return RotaVirusCompleteValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
