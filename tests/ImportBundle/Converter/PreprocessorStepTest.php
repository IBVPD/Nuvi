<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\Expression\Condition;
use NS\ImportBundle\Converter\Expression\ExpressionBuilder;
use NS\ImportBundle\Converter\PreprocessorStep;
use PHPUnit\Framework\TestCase;

class PreprocessorStepTest extends TestCase
{
    /**
     * @dataProvider getCondition
     * @param array $condParam
     * @param $value
     * @param array $item
     */
    public function testConverter(array $condParam, $value, array $item): void
    {
        $condition = new Condition($condParam, $value);

        $step = new PreprocessorStep(new ExpressionBuilder());
        $step->add('fieldOne', $condition);
        $step->process($item);
        $this->assertEquals($value, $item['fieldOne']);
    }

    public function getCondition(): array
    {
        return [
            [
                'cond' => [
                    'condition' => 'OR',
                    'rules' => [
                        ['field' => 'fieldOne', 'operator' => 'equal', 'value' => '2'],
                        ['field' => 'fieldOne', 'operator' => 'equal', 'value' => '3'],
                    ],],
                'output' => 1,
                'item' => ['fieldOne'=>2,'fieldTwo'=>2]
            ],
        ];
    }
}
