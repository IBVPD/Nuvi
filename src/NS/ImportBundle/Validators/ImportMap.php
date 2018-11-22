<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/11/18
 * Time: 8:48 PM
 */

namespace NS\ImportBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class ImportMap
 * @package NS\ImportBundle\Validators
 *
 * @Annotation
 */
class ImportMap extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
