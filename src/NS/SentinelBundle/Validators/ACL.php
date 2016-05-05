<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 05/05/16
 * Time: 11:06 AM
 */

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class ACL
 * @package NS\SentinelBundle\Validators
 *
 * @Annotation
 */
class ACL extends Constraint
{
    public $message = 'Use of deprecated role type. Please use the %type% type and set the \'%option%\' option instead';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
