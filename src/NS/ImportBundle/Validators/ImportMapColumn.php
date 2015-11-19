<?php

namespace NS\ImportBundle\Validators;

use Symfony\Component\Validator\Constraint;


/**
 * Class ImportMapColumn
 * @package NS\ImportBundle\Validators
 *
 * @Annotation
 */
class ImportMapColumn extends Constraint
{
    /**
     * @inheritDoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @inheritDoc
     */
    public function validatedBy()
    {
        return 'map_column_validator';
    }
}
