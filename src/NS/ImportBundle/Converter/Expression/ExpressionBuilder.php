<?php

namespace NS\ImportBundle\Converter\Expression;

/**
 * Class ExpressionBuilder
 * @package NS\ImportBundle\Converter\Expression
 */
class ExpressionBuilder
{
    /**
     * @var array
     */
    public $operatorMap = array(
        'equal' => '%s == %s',
        'not_equal' => '%s != %s',
        'in' => '%s in %s',
        'not_in' => '%s not in %s',
        'less' => '%s < %s',
        'less_or_equal' => '%s <= %s',
        'greater' => '%s > %s',
        'greater_or_equal' => '%s >= %s',
    );

    /**
     * @param Condition $condition
     * @return string
     */
    public function getExpression(Condition $condition)
    {
        return $this->convertRuleToExpression($condition->getRule());
    }

    /**
     * @param Rule $rule
     * @return string
     */
    public function convertRuleToExpression(Rule $rule)
    {
        if (!$rule->isComplex()) {
            return sprintf($this->getOperator($rule), $this->getField($rule), $this->getValue($rule));
        }

        $rules = array();
        foreach ($rule->getRules() as $subRule) {
            $rules[] = $this->convertRuleToExpression($subRule);
        }

        return sprintf('(%s)', implode(sprintf(') %s (', $rule->getCondition()), $rules));
    }

    /**
     * @param Rule $rule
     * @return string
     */
    public function getField(Rule $rule)
    {
        return sprintf('item["%s"]', $rule->getField());
    }

    /**
     * @param Rule $rule
     * @return mixed
     */
    public function getOperator(Rule $rule)
    {
        return $this->operatorMap[$rule->getOperator()];
    }

    /**
     * @param Rule $rule
     * @return mixed|string
     */
    public function getValue(Rule $rule)
    {
        $value = $rule->getValue();

        if (is_array($value)) {
            return sprintf('[%s]', implode(',', $value));
        } elseif (is_numeric($value)) {
            return $value;
        }

        return sprintf('\'%s\'', $value);
    }
}
