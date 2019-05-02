<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Other extends Constraint
{
    public $message = "Because of the response to '{{ field }}' the '{{ otherField }}' field should have content.";

    public $field;

    public $value;

    public $otherField;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_array($this->value)) {
            $this->value = [$this->value];
        }
    }

    public function getRequiredOptions(): array
    {
        return ['field', 'value', 'otherField'];
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
