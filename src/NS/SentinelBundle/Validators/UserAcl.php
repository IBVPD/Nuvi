<?php

namespace NS\SentinelBundle\Validators;

use \Symfony\Component\Validator\Constraint;

/**
 * Description of UserAclValidator
 *
 * @author gnat
 * @Annotation
 */
class UserAcl extends Constraint
{
    public $message = 'Invalid User account';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
