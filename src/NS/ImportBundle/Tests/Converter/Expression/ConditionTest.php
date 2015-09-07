<?php

namespace NS\ImportBundle\Tests\Converter\Expression;

use NS\ImportBundle\Converter\Expression\Condition;
use NS\ImportBundle\Converter\Expression\Rule;

class ConditionTest extends \PHPUnit_Framework_TestCase
{
    public $json = '[
           {
              "condition":{
                 "condition":"AND",
                 "rules":[
                    { "id":"name", "field":"name", "type":"string", "input":"text","operator":"equal", "value":"Mistic"},
                    {
                       "condition":"OR",
                       "rules": [
                          { "id":"category", "field":"category", "type":"integer", "input":"checkbox", "operator":"in", "value":[ "1", "2" ]},
                          { "id":"in_stock", "field":"in_stock", "type":"integer", "input":"radio", "operator":"equal", "value":"0"}
                       ]
                    }
                 ]
              },
              "output_value":5
           },
           {
              "condition":{
                 "condition":"AND",
                 "rules":[
                    { "id":"name", "field":"name", "type":"string", "input":"text", "operator":"equal", "value":"Mistic" },
                    {
                       "condition":"OR",
                       "rules":[
                          { "id":"category", "field":"category", "type":"integer", "input":"checkbox", "operator":"in", "value":[ "1", "2" ] },
                          { "id":"in_stock", "field":"in_stock", "type":"integer", "input":"radio", "operator":"equal", "value":"0" }
                       ]
                    }
                 ]
              },
              "output_value":8
           },
           {
              "condition":{
                 "condition":"AND",
                 "rules":[
                    { "id":"name", "field":"name", "type":"string", "input":"text", "operator":"equal", "value":"Mistic" },
                    {
                       "condition":"OR",
                       "rules":[
                          { "id":"category", "field":"category", "type":"integer", "input":"checkbox", "operator":"in", "value":[ "1", "2" ] },
                          { "id":"in_stock", "field":"in_stock", "type":"integer", "input":"radio", "operator":"equal", "value":"0" }
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
        $this->assertArrayHasKey('condition',$json[0]['condition']);
        $this->assertArrayHasKey('rules',$json[0]['condition'],print_r($json[0]['condition'],true));

        $cond = new Condition($json[0]['condition']['rules'],$json[0]['output_value']);
        $rules = $cond->getRules();
        $this->assertTrue(is_array($rules));
        $this->assertCount(2,$rules);
        $this->assertEquals(Rule::AND_CONDITION,$rules[0]->getCondition());
        $this->assertCount(2,$rules[1]->getRules());
//        $this->fail(print_r($rules,true));
    }
}