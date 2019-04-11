<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ACL extends Constraint
{
    public $message = 'Use of deprecated role type. Please use the %type% type and set the \'%option%\' option instead';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
