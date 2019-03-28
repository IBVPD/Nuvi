<?php

namespace NS\ImportBundle\Tests\Converter;

use DateTime;
use NS\ImportBundle\Converter\TrimInputConverter;
use PHPUnit\Framework\TestCase;
use stdClass;

class TrimInputConverterTest extends TestCase
{
    /**
     * @param array $inputData
     * @param array $outputData
     * @dataProvider getData
     */
    public function testTrimInput(array $inputData, array $outputData): void
    {
        $converter = new TrimInputConverter();
        $retData = $converter($inputData);
        $this->assertEquals($outputData, $retData);
    }

    public function getData(): array
    {
        $date = new DateTime();
        $stdClass = new stdClass();

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
