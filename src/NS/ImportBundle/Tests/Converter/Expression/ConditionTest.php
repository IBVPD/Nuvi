<?php

namespace NS\ImportBundle\Tests\Converter\Expression;

use NS\ImportBundle\Converter\Expression\Condition;
use NS\ImportBundle\Converter\Expression\Rule;

class ConditionTest extends \PHPUnit_Framework_TestCase
{
    public $json = '[
           {
              "conditions":{
                 "condition": "OR",
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
           },
           {
              "conditions":{
                 "condition": "AND",
                 "rules":[
                    { "id": "name", "field": "name", "type": "string", "input": "text", "operator": "equal", "value": "Mistic" },
                    {
                       "condition": "OR",
                       "rules":[
                          { "id": "category", "field": "category", "type": "integer", "input": "checkbox", "operator": "in", "value":[ "1", "2" ] },
                          { "id": "in_stock", "field": "in_stock", "type": "integer", "input": "radio", "operator": "equal", "value": "0" }
                       ]
                    }
                 ]
              },
              "output_value":8
           },
           {
              "conditions":{
                 "condition": "OR",
                 "rules":[
                    { "id": "name", "field": "name", "type": "string", "input": "text", "operator": "equal", "value": "Mistic" },
                    {
                       "condition": "OR",
                       "rules":[
                          { "id": "category", "field": "category", "type": "integer", "input": "checkbox", "operator": "in", "value":[ "1", "2" ] },
                          { "id": "in_stock", "field": "in_stock", "type": "integer", "input": "radio", "operator": "equal", "value": "0" }
                       ]
                    }
                 ]
              },
              "output_value":99
           }
        ]';

    public function testCondition()
    {
        $json = json_decode($this->json,true);

        $this->assertCount(3,$json);
        $this->assertArrayHasKey('condition',$json[0]['conditions']);
        $this->assertArrayHasKey('rules',$json[0]['conditions']);

        $cond = new Condition($json[0]['conditions'],$json[0]['output_value']);
        $this->assertEquals(5,$cond->getValue());

        $rule = $cond->getRule();
        $this->assertInstanceOf('NS\ImportBundle\Converter\Expression\Rule',$rule);
        $this->assertEquals(Rule::OR_CONDITION,$rule->getCondition());

        $rules = $rule->getRules();
        $this->assertTrue(is_array($rules));
        $this->assertCount(2,$rules);
        $this->assertCount(2,$rules[1]->getRules());
    }
}
