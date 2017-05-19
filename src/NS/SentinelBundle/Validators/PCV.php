<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/05/17
 * Time: 10:01 AM
 */

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class PCV
 * @package NS\SentinelBundle\Validators
 *
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
