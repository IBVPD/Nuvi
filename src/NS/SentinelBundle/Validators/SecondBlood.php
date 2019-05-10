<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SecondBlood extends Constraint
{
    public $message = 'Because there were two blood samples this field is required';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
