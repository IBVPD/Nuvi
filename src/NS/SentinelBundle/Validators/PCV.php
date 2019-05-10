<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PCV extends Constraint
{
    public $message = 'When PCV has been received, this field is required';

    /**
     * @inheritDoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
