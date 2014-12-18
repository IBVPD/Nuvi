<?php

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Description of Other
 *
 * @author gnat
 * @Annotation
 */
class AllOther extends Constraint
{
    public $constraints = array();

    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!is_array($this->constraints))
            $this->constraints = array($this->constraints);

        foreach ($this->constraints as $constraint)
        {
            if (!$constraint instanceof Constraint)
                throw new ConstraintDefinitionException(sprintf('The value %s is not an instance of Constraint in constraint %s', $constraint, __CLASS__));

            if ($constraint instanceof Valid)
                throw new ConstraintDefinitionException(sprintf('The constraint Valid cannot be nested inside constraint %s. You can only declare the Valid constraint directly on a field or method.', __CLASS__));
        }
    }

    public function getDefaultOption()
    {
        return 'constraints';
    }

    public function getRequiredOptions()
    {
        return array('constraints');
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
