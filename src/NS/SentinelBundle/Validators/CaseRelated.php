<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CaseRelated extends Constraint
{
    /** @var string */
    public $message;

    /** @var string */
    public $caseField;

    /** @var string */
    public $caseFieldValue;
}
