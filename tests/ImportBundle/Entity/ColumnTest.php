<?php

namespace NS\ImportBundle\Tests\Entity;

use NS\ImportBundle\Entity\Column;

class ColumnTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $name
     * @param $ignored
     * @param $mapper
     * @param $expectedResult
     *
     * @dataProvider getMapperColumns
     */
    public function testHasMapper($name, $ignored, $mapper, $expectedResult)
    {
        $col = new Column();
        $col->setName($name);
        $col->setIgnored($ignored);
        $col->setMapper($mapper);

        $this->assertEquals($expectedResult, $col->hasMapper());
    }

    public function getMapperColumns()
    {
        return [
            ['column1',true,'column2',false],
            ['column1',true, null, false],
            ['column1',false,'',false],
            ['column1',false,'column1',false],
            ['column1',false,'column2',true],
        ];
    }

    /**
     * @param $name
     * @param $ignored
     * @param $converter
     * @param $expectedResult
     *
     * @dataProvider getConverterColumns
     */
    public function testHasConverter($name, $ignored, $converter, $expectedResult)
    {
        $col = new Column();
        $col->setName($name);
        $col->setIgnored($ignored);
        $col->setConverter($converter);

        $this->assertEquals($expectedResult, $col->hasConverter());
    }

    public function getConverterColumns()
    {
        return [
            ['column1',true,'column2',false],
            ['column1',true, null, false],
            ['column1',false,'',false],
            ['column1',false,'column2',true],
        ];
    }

    /**
     * @param $name
     * @param $ignored
     * @param $preProcessor
     * @param $expectedResult
     *
     * @dataProvider getPreProcessorColumns
     */
    public function testHasPreProcessor($name, $ignored, $preProcessor, $expectedResult)
    {
        $col = new Column();
        $col->setName($name);
        $col->setIgnored($ignored);
        $col->setPreProcessor($preProcessor);

        $this->assertEquals($expectedResult, $col->hasPreProcessor());
    }

    public function getPreProcessorColumns()
    {
        return [
            ['column1',true,'column2',false],
            ['column1',true, null, false],
            ['column1',true, '[]', false],
            ['column1',false, null, false],
            ['column1',false, '[]', false],
            ['column1',false, '[{"conditions": {}}]', true],
        ];
    }
}
