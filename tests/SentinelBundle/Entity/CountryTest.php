<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Entity\Country;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    public function testConstructor(): void
    {
        $country = new Country('CODE', 'CountryName');
        $this->assertEquals('CODE', $country->getCode());
        $this->assertEquals('CountryName', $country->getName());
    }
}
