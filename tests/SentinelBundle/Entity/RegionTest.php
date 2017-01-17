<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\Region;

class RegionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $region = new Region('CODE', 'Region Name');
        $this->assertEquals('CODE', $region->getCode());
        $this->assertEquals('Region Name', $region->getName());
    }
}
