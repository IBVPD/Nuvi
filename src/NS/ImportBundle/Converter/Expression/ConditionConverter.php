<?php

namespace NS\ImportBundle\Converter\Expression;

class ConditionConverter
{
    /**
     * @param array $conditions
     * @return string
     */
    public function toString(array $conditions)
    {
        return implode("\n", $this->convert($conditions));
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function toArray(array $conditions)
    {
        return $this->convert($conditions);
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function convert(array $conditions)
    {
        $builder = new ExpressionBuilder();
        $expr = array();

        foreach ($conditions as $id=>$value) {
            $condition = new Condition($value['conditions'], $value['output_value']);

            $expr[$id] = sprintf('if (%s) then %s', $builder->convertRuleToExpression($condition->getRule()), $condition->getValue());
        }

        return $expr;
    }
}
