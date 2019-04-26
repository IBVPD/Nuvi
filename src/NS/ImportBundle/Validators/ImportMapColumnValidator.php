<?php

namespace NS\ImportBundle\Validators;

use function get_class;
use InvalidArgumentException;
use NS\ImportBundle\Converter\ColumnChooser;
use NS\ImportBundle\Entity\Column;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ImportMapColumnValidator extends ConstraintValidator
{
    /** @var ColumnChooser */
    private $columnChooser;

    /** @var array */
    private $complexChoices;

    /**
     * ImportMapColumnValidator constructor.
     * @param $columnChooser
     */
    public function __construct(ColumnChooser $columnChooser)
    {
        $this->columnChooser = $columnChooser;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Column) {
            throw new InvalidArgumentException(sprintf('Expected object of class %s received %s', Column::class, get_class($value)));
        }

        if (empty($this->complexChoices)) {
            $this->initializeChoices($value->getMap()->getClass());
        }

        // if db column is not scalar, ensure we have a 'validator'
        if (!$value->hasConverter() && isset($this->complexChoices[$value->getMapper()])) {
            $this->context
                ->buildViolation('This column has a field that requires a validator, but none were selected')
                ->atPath('converter')
                ->addViolation();
        }
    }

    /**
     * @param $class
     */
    public function initializeChoices($class)
    {
        $this->complexChoices = $this->columnChooser->getComplexChoices($class);
    }
}
