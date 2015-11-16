<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\Country;

class CountryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $country = new Country('CODE','CountryName');
        $this->assertEquals('CODE',$country->getCode());
        $this->assertEquals('CountryName',$country->getName());
    }
}
