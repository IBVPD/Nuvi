<?php

namespace NS\ImportBundle\Converter\Expression;

class Condition
{
    private $rules = array();
    private $outputValue;

    public function __construct($rules, $value)
    {
        $this->outputValue = $value;
        foreach($rules as $rule) {
            $this->rules[] = new Rule($rule);
        }
    }

    /**
     * @return Rule
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return mixed
     */
    public function getOutputValue()
    {
        return $this->outputValue;
    }
}