<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\WarningConverter;
use NS\ImportBundle\Tests\TestArrayChoice;
use NS\UtilBundle\Form\Types\ArrayChoice;
use PHPUnit\Framework\TestCase;

class WarningConverterTest extends TestCase
{
    public function testNoWarning(): void
    {
        $data      = ['var1' => new TestArrayChoice(), 'var2' => 'nothing', 'var3' => null];
        $converter = new WarningConverter();
        $retData   = $converter->__invoke($data);
        $this->assertArrayNotHasKey('warning', $retData);
        $this->assertEquals($data, $retData);
    }

    public function testHasWarning(): void
    {
        $data      = ['var1' => new TestArrayChoice(ArrayChoice::OUT_OF_RANGE), 'var2' => 'nothing', 'var3' => null];
        $converter = new WarningConverter();
        $retData   = $converter->__invoke($data);
        $this->assertArrayHasKey('warning', $retData);
        $this->assertTrue($retData['warning']);
        $this->assertEquals(array_merge($data, ['warning' => true]), $retData);
    }
}
