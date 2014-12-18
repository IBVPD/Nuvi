<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Description of Other
 *
 * @author gnat
 * @Annotation
 */
class Other extends Constraint
{
    public $message = 'The other fields should have content.';

    public $field;

    public $value;

    public $otherField;

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return array('field', 'value', 'otherField');
    }
}
