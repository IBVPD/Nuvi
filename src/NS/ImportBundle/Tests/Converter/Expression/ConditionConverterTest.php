<?php

namespace NS\ImportBundle\Tests\Converter\Expression;

use NS\ImportBundle\Converter\Expression\ConditionConverter;

class ConditionConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $json
     * @param $expectedResults
     *
     * @param array $perRows
     * @dataProvider getJsonConditions
     */
    public function testConversionToArray($json,$expectedResults,array $perRows)
    {
        $conditions = json_decode($json,true);
        $converter = new ConditionConverter();
        $results = $converter->toArray($conditions);
        $this->assertCount($expectedResults,$results);
        $this->assertEquals($perRows[0],$results[0]);
    }

    public function getJsonConditions()
    {
        return array(
            array(
                '[{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"AFG-103"}]},"output_value":"AFG-HR"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"AFG-101"}]},"output_value":"AFG-IG"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"PAK-1306"}]},"output_value":"PAK-311"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"PAK-1303"}]},"output_value":"PAK-312"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"PAK-1301"}]},"output_value":"PAK-315"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"SDN-1807"}]},"output_value":"SDN-FO"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"SDN-1804"}]},"output_value":"SDN-GG"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"SDN-1803"}]},"output_value":"SDN-GM"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"SDN-1802"}]},"output_value":"SDN-KK"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"SDN-1801"}]},"output_value":"SDN-KO"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"YEM-2204"}]},"output_value":"YEM-AW"},{"conditions":{"condition":"AND","rules":[{"id":"site_code","field":"site_code","type":"string","input":"text","operator":"equal","value":"YEM-2201"}]},"output_value":"YEM-TS"}]',
                12,
                array(
                    'if (item["site_code"] == \'AFG-103\') then AFG-HR',
                )
            ),
            array(
                '[{"conditions":{"condition":"AND","rules":[{"id":"RotaEIAR","field":"RotaEIAR","type":"string","input":"text","operator":"equal","value":"2"}]},"output_value":"0"},{"conditions":{"condition":"AND","rules":[{"id":"RotaEIAR","field":"RotaEIAR","type":"string","input":"text","operator":"equal","value":"9"}]},"output_value":"99"}]',
                2,
                array(
                    'if (item["RotaEIAR"] == 2) then 0',
                )
            )
        );
    }
}
