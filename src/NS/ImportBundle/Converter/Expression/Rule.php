<?php

namespace NS\ImportBundle\Converter\Expression;

class Rule
{
    const AND_CONDITION = 0;
    const OR_CONDITION  = 1;

    private $rules = array();
    private $condition;
    private $field;
    private $operator;
    private $value;

    public function __construct(array $json)
    {
        if(isset($json['condition']) && count($json['rules']) > 1)  {
            $this->condition = ($json['condition'] == 'AND') ? self::AND_CONDITION:self::OR_CONDITION;
            foreach($json['rules'] as $jsonRule) {
                $this->rules[] = new Rule($jsonRule);
            }
        } elseif(isset($json['rules'])) {
            $rule = &$json['rules'][0];
            $this->field = $rule['field'];
            $this->operator = $rule['operator'];
            $this->value = $rule['value'];
        } else {
            $this->field = $json['field'];
            $this->operator = $json['operator'];
            $this->value = $json['value'];
        }
        
    }

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return mixed
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}