<?php

namespace NS\ImportBundle\Tests\Converter\Expression;

use NS\ImportBundle\Converter\Expression\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    public $simpleJson = '{
                    "condition": "AND",
                    "rules": [ { "id": "name", "field": "name", "type": "string", "input": "text", "operator": "equal", "value": "Mistic" } ]
                    }';

    public $json = '{
                    "condition": "AND",
                    "rules": [
                        { "id": "name", "field": "name", "type": "string", "input": "text", "operator": "equal", "value": "Mistic" },
                        {
                            "condition": "OR",
                            "rules": [
                                { "id": "category", "field": "category", "type": "integer", "input": "checkbox", "operator": "in", "value": [ "1", "2" ] },
                                { "id": "in_stock", "field": "in_stock", "type": "integer", "input": "radio", "operator": "equal", "value": "0"}
                            ]
                        }
                    ]
                }';

    public function testSimpleRule()
    {
        $json = \json_decode($this->simpleJson,true);
        $this->assertTrue(is_array($json),gettype($json).' '.json_last_error());
        $this->assertArrayHasKey('rules',$json);
        $this->assertArrayHasKey('condition',$json);
        $this->assertCount(1,$json['rules']);
        $rule = new Rule($json);
        $this->assertNull($rule->getCondition());
        $this->assertEquals('name',$rule->getField());
        $this->assertEquals('equal',$rule->getOperator());
        $this->assertEquals('Mistic',$rule->getValue());
    }

    public function testBuildRules()
    {
        $json = \json_decode($this->json,true);
        $this->assertTrue(is_array($json),gettype($json).' '.json_last_error());
        $this->assertArrayHasKey('rules',$json);
        $this->assertArrayHasKey('condition',$json);
        $this->assertCount(2,$json['rules']);

        $rule = new Rule($json);
        $this->assertEquals(Rule::AND_CONDITION,$rule->getCondition());

        $subRules = $rule->getRules();
        $this->assertCount(2,$subRules);

        $firstRule = $subRules[0];
        $this->assertNull($firstRule->getCondition());
        $this->assertEquals('name',$firstRule->getField());
        $this->assertEquals('equal',$firstRule->getOperator());
        $this->assertEquals('Mistic',$firstRule->getValue());

        $secondRules = $subRules[1];
        $this->assertEquals(Rule::OR_CONDITION,$secondRules->getCondition());
        $this->assertCount(2,$secondRules->getRules());
    }
}