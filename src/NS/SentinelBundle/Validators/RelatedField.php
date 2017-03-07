<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 27/02/17
 * Time: 1:54 PM
 */

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class RelatedField
 * @package NS\SentinelBundle\Validators
 *
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
