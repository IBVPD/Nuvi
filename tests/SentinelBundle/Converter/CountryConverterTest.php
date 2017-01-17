<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\CountryConverter;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;

class CountryConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectKeys()
    {
        $objects = $this->getObjects();
        $this->assertEquals(['C1', 'C2', 'C3'], array_keys($objects));
        $this->assertCount(3, $objects);
    }

    public function testCountryConverter()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $convertedObj = $converter->__invoke('C1');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Country', $convertedObj);
        $this->assertEquals('C1', $convertedObj->getCode());
        $this->assertEquals('CName1', $convertedObj->getName());

        $convertedObj = $converter->__invoke('C2');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Country', $convertedObj);
        $this->assertEquals('C2', $convertedObj->getCode());

        $this->assertEquals('Country', $converter->getName());
    }

    /**
     * @expectedException \NS\SentinelBundle\Exceptions\NonExistentObjectException
     * @expectedExceptionMessage Unable to find country for C5
     */
    public function testCountryConverterNonExistentCountryException()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $converter->__invoke('C5');
    }

    /**
     * @expectedException \NS\SentinelBundle\Exceptions\NonExistentObjectException
     * @expectedExceptionMessage Country C3 is inactive, import disabled!
     */
    public function testCountryConverterInactiveCountryException()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $converter->__invoke('C3');
    }

    public function testCountryByName()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $obj = $converter->__invoke('CName2');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Country', $obj);
        $this->assertEquals('CName2', $obj->getName());
        $this->assertEquals('C2', $obj->getCode());
    }

    public function testCountryByCaseName()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $obj = $converter->__invoke('cname2');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Country', $obj);
        $this->assertEquals('CName2', $obj->getName());
        $this->assertEquals('C2', $obj->getCode());
    }

    private function getMockObjectManager()
    {
        $repo = $this->createMock('NS\SentinelBundle\Repository\CountryRepository');

        $em = $this->createMock('Doctrine\Common\Persistence\ObjectManager');

        $objects = $this->getObjects();
        $repo->expects($this->any())
            ->method('getChain')
            ->with(null, true)
            ->willReturn($objects);

        $em->expects($this->any())
            ->method('getRepository')
            ->with('NSSentinelBundle:Country')
            ->willReturn($repo);

        return $em;
    }

    private function getObjects()
    {
        $region = new Region('RName', 'Region Name');

        $country1 = new Country('C1', 'CName1');
        $country1->setRegion($region);
        $country1->setActive(true);

        $country2 = new Country('C2', 'CName2');
        $country2->setRegion($region);
        $country2->setActive(true);

        $country3 = new Country('C3', 'CName3');
        $country3->setRegion($region);
        $country3->setActive(false);

        return [$country1->getCode() => $country1, $country2->getCode() => $country2, $country3->getCode() => $country3];
    }
}
