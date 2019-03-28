<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\UnsetMappingItemConverter;
use PHPUnit\Framework\TestCase;

class UnsetMappingItemConverterTest extends TestCase
{
    public function testUnsetMappingProperlyUnsetsValue(): void
    {
        $data      = ['field1' => 1, 'field2' => 2, 'field3' => 3];
        $converter = new UnsetMappingItemConverter(['field1', 'field2']);
        $converter->process($data);

        $this->assertEquals(['field3' => 3], $data);
    }
}
