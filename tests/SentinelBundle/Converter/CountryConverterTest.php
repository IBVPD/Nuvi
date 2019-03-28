<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\CountryConverter;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Exceptions\NonExistentObjectException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Repository\CountryRepository;

class CountryConverterTest extends TestCase
{
    public function testObjectKeys(): void
    {
        $objects = $this->getObjects();
        $this->assertEquals(['C1', 'C2', 'C3'], array_keys($objects));
        $this->assertCount(3, $objects);
    }

    public function testCountryConverter(): void
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $convertedObj = $converter->__invoke('C1');
        $this->assertInstanceOf(Country::class, $convertedObj);
        $this->assertEquals('C1', $convertedObj->getCode());
        $this->assertEquals('CName1', $convertedObj->getName());

        $convertedObj = $converter->__invoke('C2');
        $this->assertInstanceOf(Country::class, $convertedObj);
        $this->assertEquals('C2', $convertedObj->getCode());

        $this->assertEquals('Country', $converter->getName());
    }

    public function testCountryConverterNonExistentCountryException(): void
    {
        $this->expectException(NonExistentObjectException::class);
        $this->expectExceptionMessage('Unable to find country for C5');

        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $converter->__invoke('C5');
    }

    public function testCountryConverterInactiveCountryException(): void
    {
        $this->expectException(NonExistentObjectException::class);
        $this->expectExceptionMessage('Country C3 is inactive, import disabled!');

        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $converter->__invoke('C3');
    }

    public function testCountryByName(): void
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $obj = $converter->__invoke('CName2');
        $this->assertInstanceOf(Country::class, $obj);
        $this->assertEquals('CName2', $obj->getName());
        $this->assertEquals('C2', $obj->getCode());
    }

    public function testCountryByCaseName(): void
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new CountryConverter($entityMgr);

        $obj = $converter->__invoke('cname2');
        $this->assertInstanceOf(Country::class, $obj);
        $this->assertEquals('CName2', $obj->getName());
        $this->assertEquals('C2', $obj->getCode());
    }

    private function getMockObjectManager(): MockObject
    {
        $repo    = $this->createMock(CountryRepository::class);
        $em      = $this->createMock(ObjectManager::class);
        $objects = $this->getObjects();

        $repo
            ->method('getChain')
            ->with(null, true)
            ->willReturn($objects);

        $em
            ->method('getRepository')
            ->with('NSSentinelBundle:Country')
            ->willReturn($repo);

        return $em;
    }

    private function getObjects(): array
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
