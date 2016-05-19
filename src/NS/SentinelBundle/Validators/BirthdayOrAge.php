<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 18/05/16
 * Time: 3:40 PM
 */

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class BirthdayOrDobKnown
 * @package NS\SentinelBundle\Validators
 * @Annotation
 */
class BirthdayOrAge extends Constraint
{
    public $message = 'The birthdate or age is required';

    /**
     * @inheritDoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
