<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class Done
 * @package NS\SentinelBundle\Validators
 */
class Done extends Constraint
{
    /**
     * @var string
     */
    public $resultField;

    /**
     * @var string
     */
    public $tripleChoiceField;

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
