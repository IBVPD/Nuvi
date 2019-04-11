<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * @Annotation
 */
class AllOther extends Constraint
{
    public $constraints = [];

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!is_array($this->constraints)) {
            $this->constraints = [$this->constraints];
        }

        foreach ($this->constraints as $constraint) {
            if (!$constraint instanceof Constraint) {
                throw new ConstraintDefinitionException(sprintf('The value %s is not an instance of Constraint in constraint %s', $constraint, __CLASS__));
            }

            if ($constraint instanceof Valid) {
                throw new ConstraintDefinitionException(sprintf('The constraint Valid cannot be nested inside constraint %s. You can only declare the Valid constraint directly on a field or method.', __CLASS__));
            }
        }
    }

    public function getDefaultOption(): string
    {
        return 'constraints';
    }

    public function getRequiredOptions(): array
    {
        return ['constraints'];
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
