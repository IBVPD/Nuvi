<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 20/12/18
 * Time: 4:34 PM
 */

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TacPhaseTwo extends Constraint
{
    public $message = 'When the site is part of Tac Phase II, these fields are required';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
