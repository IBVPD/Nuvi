<?php

namespace NS\ImportBundle\Converter\Expression;

/**
 * Class Condition
 * @package NS\ImportBundle\Converter\Expression
 */
class Condition
{
    /**
     * @var Rule
     */
    private $rule;

    /**
     * @var
     */
    private $value;

    /**
     * @param $rules
     * @param $value
     */
    public function __construct($rule, $value)
    {
        $this->value = $value;
        $this->rule = new Rule($rule);
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
