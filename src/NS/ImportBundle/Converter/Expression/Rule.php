<?php

namespace NS\ImportBundle\Converter\Expression;

/**
 * Class Rule
 * @package NS\ImportBundle\Converter\Expression
 */
class Rule
{
    const AND_CONDITION = '&&';
    const OR_CONDITION  = '||';

    /**
     * @var array
     */
    private $rules = array();

    /**
     * @var int
     */
    private $condition;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string
     */
    private $value;

    /**
     * @var boolean
     */
    private $complex = false;

    /**
     * @param array $json
     */
    public function __construct(array $json)
    {
        if(isset($json['condition']) && count($json['rules']) > 1)  {
            $this->condition = ($json['condition'] == 'AND') ? self::AND_CONDITION : self::OR_CONDITION;
            $this->complex = true;
            foreach($json['rules'] as $jsonRule) {
                $this->rules[] = new Rule($jsonRule);
            }
        } else {
            $rule = (isset($json['rules'])) ? $json['rules'][0]: $json;

            $this->field = $rule['field'];
            $this->operator = $rule['operator'];
            $this->value = $rule['value'];
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

    /**
     * @return bool
     */
    public function isComplex()
    {
        return $this->complex;
    }
}