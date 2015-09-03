<?php

namespace NS\ImportBundle\Entity\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Import
 * @package NS\ImportBundle\Entity\Validator
 *
 * @Annotation
 */
class Import extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}