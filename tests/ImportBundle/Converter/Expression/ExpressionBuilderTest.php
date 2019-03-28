<?php

namespace NS\ImportBundle\Tests\Converter\Expression;

use NS\ImportBundle\Converter\Expression\Condition;
use NS\ImportBundle\Converter\Expression\ExpressionBuilder;
use NS\ImportBundle\Converter\Expression\Rule;
use PHPUnit\Framework\TestCase;

class ExpressionBuilderTest extends TestCase
{
    public $json = '{
              "condition":{
                 "condition": "AND",
                 "rules":[
                    { "id": "name", "field": "name", "type": "string", "input": "text","operator": "equal", "value": "Mistic"},
                    {
                       "condition": "OR",
                       "rules": [
                          { "id": "category", "field": "category", "type": "integer", "input": "checkbox", "operator": "in", "value":[ "1", "2" ]},
                          { "id": "in_stock", "field": "in_stock", "type": "integer", "input": "radio", "operator": "equal", "value": "0"}
                       ]
                    }
                 ]
              },
              "output_value":5
           }';

    public function testComplexRules(): void
    {
        $json = json_decode($this->json, true);
        $this->assertInternalType('array', $json);
        $this->assertArrayHasKey('condition', $json);
        $this->assertArrayHasKey('output_value', $json);

        $condition = new Condition($json['condition'], $json['output_value']);
        $builder = new ExpressionBuilder();
        $expression = $builder->getExpression($condition);
        $this->assertEquals('(item["name"] == \'Mistic\') && ((item["category"] in [1,2]) || (item["in_stock"] == 0))', $expression);
    }

    /**
     * @dataProvider getRules
     * @param Rule $rule
     * @param $fieldOutput
     * @param $operator
     * @param $expression
     */
    public function testSimpleBuilderFunctions(Rule $rule, $fieldOutput, $operator, $expression): void
    {
        $builder = new ExpressionBuilder();
        $this->assertEquals($fieldOutput, $builder->getField($rule));
        $this->assertEquals($operator, $builder->getOperator($rule));
        $this->assertEquals($expression, $builder->convertRuleToExpression($rule));
    }

    public function getRules(): array
    {
        return [
            [
                'rule' => new Rule(['field' => 'field', 'operator' => 'equal', 'value'=> 1]),
                'fieldOutput' => 'item["field"]',
                'operator' => '%s == %s',
                'expression' => 'item["field"] == 1',
            ],
            [
                'rule' => new Rule(['field' => 'field1', 'operator' => 'not_equal', 'value' => 2]),
                'fieldOutput' => 'item["field1"]',
                'operator' => '%s != %s',
                'expression' => 'item["field1"] != 2',
            ],
            [
                'rule' => new Rule(['field' => 'field2', 'operator' => 'in', 'value'=> [1, 2, 3]]),
                'fieldOutput' => 'item["field2"]',
                'operator' => '%s in %s',
                'expression' => 'item["field2"] in [1,2,3]',
            ],
            [
                'rule' => new Rule(['field' => 'field4', 'operator' => 'not_in', 'value'=> [3, 2, 1]]),
                'fieldOutput' => 'item["field4"]',
                'operator' => '%s not in %s',
                'expression' => 'item["field4"] not in [3,2,1]',
            ],
            [
                'rule' => new Rule(['field' => 'field5', 'operator' => 'less', 'value'=> 1]),
                'fieldOutput' => 'item["field5"]',
                'operator' => '%s < %s',
                'expression' => 'item["field5"] < 1',
            ],
            [
                'rule' => new Rule(['field'=>'field', 'operator'=>'less_or_equal', 'value'=> 1]),
                'fieldOutput' => 'item["field"]',
                'operator' => '%s <= %s',
                'expression' => 'item["field"] <= 1',
            ],
            [
                'rule' => new Rule(['field'=>'field7', 'operator'=>'greater', 'value'=> 1]),
                'fieldOutput' => 'item["field7"]',
                'operator' => '%s > %s',
                'expression' => 'item["field7"] > 1',
            ],
            [
                'rule' => new Rule(['field'=>'field8', 'operator'=>'greater_or_equal', 'value'=> 1]),
                'fieldOutput' => 'item["field8"]',
                'operator' => '%s >= %s',
                'expression' => 'item["field8"] >= 1',
            ],
            [
                'rule' => new Rule(['field'=>'field8', 'operator'=>'equal', 'value'=> 'ARM-01']),
                'fieldOutput' => 'item["field8"]',
                'operator' => '%s == %s',
                'expression' => 'item["field8"] == \'ARM-01\'',
            ]
        ];
    }
}
