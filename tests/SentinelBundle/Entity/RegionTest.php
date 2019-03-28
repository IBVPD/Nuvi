<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\Region;
use PHPUnit\Framework\TestCase;

class RegionTest extends TestCase
{
    public function testConstructor(): void
    {
        $region = new Region('CODE', 'Region Name');
        $this->assertEquals('CODE', $region->getCode());
        $this->assertEquals('Region Name', $region->getName());
    }
}
