<?php

namespace NS\ImportBundle\Converter\Expression;

class ExpressionBuilder
{
    public $operatorMap = array(
        'equal' => '%s == %s',
        'not_equal' => '%s != %s',
        'in' => '%s in %s',
        'not_in' => '%s not in %s',
        'less' => '%s < %s',
        'less_or_equal' => '%s <= %s',
        'greater' => '%s > %s',
        'greater_or_equal' => '%s >= %s',
//        {type: 'between',          nb_inputs: 2, multiple: false, apply_to: ['number', 'datetime']},
//        {type: 'not_between',      nb_inputs: 2, multiple: false, apply_to: ['number', 'datetime']},
//        {type: 'begins_with',      nb_inputs: 1, multiple: false, apply_to: ['string']},
//        {type: 'not_begins_with',  nb_inputs: 1, multiple: false, apply_to: ['string']},
//        {type: 'contains',         nb_inputs: 1, multiple: false, apply_to: ['string']},
//        {type: 'not_contains',     nb_inputs: 1, multiple: false, apply_to: ['string']},
//        {type: 'ends_with',        nb_inputs: 1, multiple: false, apply_to: ['string']},
//        {type: 'not_ends_with',    nb_inputs: 1, multiple: false, apply_to: ['string']},
//        {type: 'is_empty',         nb_inputs: 0, multiple: false, apply_to: ['string']},
//        {type: 'is_not_empty',     nb_inputs: 0, multiple: false, apply_to: ['string']},
//        {type: 'is_null',          nb_inputs: 0, multiple: false, apply_to: ['string', 'number', 'datetime', 'boolean']},
//        {type: 'is_not_null',      nb_inputs: 0, multiple: false, apply_to: ['string', 'number', 'datetime', 'boolean']}
    );

    public function getExpression(Condition $condition)
    {
        return $this->convertRuleToExpression($condition->getRule());
    }

    public function convertRuleToExpression(Rule $rule)
    {
        if (!$rule->isComplex()) {
            return sprintf($this->getOperator($rule), $this->getField($rule), $this->getValue($rule));
        }

        $rules = array();
        foreach($rule->getRules() as $subRule) {
            $rules[] = $this->convertRuleToExpression($subRule);
        }

        return sprintf('(%s)',implode(sprintf(') %s (',$rule->getCondition()),$rules));
    }

    public function getField(Rule $rule)
    {
        return sprintf('item["%s"]', $rule->getField());
    }

    public function getOperator(Rule $rule)
    {
        return $this->operatorMap[$rule->getOperator()];
    }

    public function getValue(Rule $rule)
    {
        if (is_array($rule->getValue())) {
            return sprintf('[%s]', implode(',', $rule->getValue()));
        }

        return $rule->getValue();
    }
}