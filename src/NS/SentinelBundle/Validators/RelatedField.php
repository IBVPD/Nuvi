<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RelatedField extends Constraint
{
    /** @var string */
    public $sourceField;

    /** @var array */
    public $sourceValue = [];

    /** @var array */
    public $fields = [];

    /** @var string */
    public $message = "Due to response for '%source%' field, related field '%field%' is required";

    /**
     * @inheritDoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
