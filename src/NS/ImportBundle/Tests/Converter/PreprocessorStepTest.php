<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\Expression\Condition;
use NS\ImportBundle\Converter\Expression\ExpressionBuilder;
use NS\ImportBundle\Converter\PreprocessorStep;

class PreprocessorStepTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getCondition
     * @param array $condParam
     * @param $value
     * @param array $item
     */
    public function testConverter(array $condParam, $value, array $item)
    {
        $condition = new Condition($condParam, $value);

        $step = new PreprocessorStep(new ExpressionBuilder());
        $step->add('fieldOne', $condition);
        $step->process($item);
        $this->assertEquals($value, $item['fieldOne']);
    }

    public function getCondition()
    {
        return array(
            array(
                'cond' => array(
                    'condition' => 'OR',
                    'rules' => array(
                        array('field' => 'fieldOne', 'operator' => 'equal', 'value' => '2'),
                        array('field' => 'fieldOne', 'operator' => 'equal', 'value' => '3'),
                    ),),
                'output' => 1,
                'item' => array('fieldOne'=>2,'fieldTwo'=>2)
            ),
        );
    }
}
