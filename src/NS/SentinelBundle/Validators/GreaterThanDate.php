<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class GreaterThanDate
 * @package NS\SentinelBundle\Validators
 *
 * @Annotation
 */
class GreaterThanDate extends Constraint
{
    /**
     * @var string
     */
    public $lessThanField;

    /**
     * @var string
     */
    public $greaterThanField;

    /**
     * @var string
     */
    public $message;

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return ['lessThanField','greaterThanField'];
    }
}
