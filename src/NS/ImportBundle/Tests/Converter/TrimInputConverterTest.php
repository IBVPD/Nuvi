<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\TrimInputConverter;

class TrimInputConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $inputData
     * @param array $outputData
     * @dataProvider getData
     */
    public function testTrimInput(array $inputData, array $outputData)
    {
        $converter = new TrimInputConverter();
        $retData = call_user_func($converter, $inputData);
        $this->assertEquals($outputData, $retData);
    }

    public function getData()
    {
        $date = new \DateTime();
        $stdClass = new \stdClass();

        return [
            [
                'inputData' => [],
                'outputData' => [],
            ],
            [
                'inputData' => [
                    'field1' => '  0',
                    'field2' => ' 1 ',
                    'subarray' => [
                        'field1' => 1,
                        'field2' => $date,
                        'field3'=> '  '.'1',
                    ],
                    'field3'=> $stdClass
                ],
                'outputData' => [
                    'field1' => '0',
                    'field2' => '1',
                    'subarray' => [
                        'field1' => 1,
                        'field2' => $date,
                        'field3'=> '1',
                    ],
                    'field3'=> $stdClass
                ],
            ],
        ];
    }
}
