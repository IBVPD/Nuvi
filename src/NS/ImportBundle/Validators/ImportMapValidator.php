<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/11/18
 * Time: 8:49 PM
 */

namespace NS\ImportBundle\Validators;

use NS\ImportBundle\Entity\Map;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ImportMapValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Map) {
            throw new \InvalidArgumentException(sprintf('Expected object of class %s received %s', Map::class, \get_class($value)));
        }

        $mappers = [];
        foreach ($value->getColumns() as $index => $column) {
            if ($column->getMapper() && \in_array($column->getMapper(), $mappers, true)) {
                $this->context
                    ->buildViolation(sprintf('The column %s is targeting a field that has already been mapped.', $column->getName()))
                    ->atPath('columns['.$index.'].mapper')
                    ->addViolation();
            }

            $mappers[] = $column->getMapper();
        }
    }
}
