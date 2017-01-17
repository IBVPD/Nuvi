<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 11/05/16
 * Time: 2:11 PM
 */

namespace NS\SentinelBundle\Tests\Entity;


use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Region;

class BaseCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testClone()
    {
        $region  = new Region('REG','Region');
        $country = new Country('CA','Canada');
        $country->setRegion($region);

        $case = new IBD();
        $case->setId('CA-XXX-16-000001');
        $case->setCountry($country);

        $this->assertTrue($case->isUnlinked());
        $this->assertEquals($case->getId(),'CA-XXX-16-000001');
        $this->assertEquals($case->getCountry(), $country);
        $this->assertEquals($case->getRegion(), $region);

        $clonedCase = clone $case;
        $this->assertNull($clonedCase->getId());
        $this->assertEquals($clonedCase->getCountry(), $country);
        $this->assertEquals($clonedCase->getRegion(), $region);
    }
}
