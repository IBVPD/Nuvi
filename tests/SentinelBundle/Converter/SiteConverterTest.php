<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Converter\SiteConverter;
use NS\SentinelBundle\Exceptions\NonExistentSiteException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Repository\SiteRepository;

/**
 * Description of SiteTest
 *
 * @author gnat
 */
class SiteConverterTest extends TestCase
{

    public function testSiteConverter(): void
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new SiteConverter($entityMgr);

        $convertedObj = $converter->__invoke('S1');
        $this->assertInstanceOf(Site::class, $convertedObj);
        $this->assertEquals('S1', $convertedObj->getCode());

        $convertedObj = $converter->__invoke('S2');
        $this->assertInstanceOf(Site::class, $convertedObj);
        $this->assertEquals('S2', $convertedObj->getCode());

        $this->assertEquals('Site', $converter->getName());
    }

    public function testSiteConverterNonExistentSiteException(): void
    {
        $this->expectExceptionMessage(NonExistentSiteException::class);
        $this->expectExceptionMessage('Unable to find site for S5');

        $entityMgr = $this->getMockObjectManager();
        $converter = new SiteConverter($entityMgr);

        $converter->__invoke('S5');
    }

    public function testSiteConverterInactiveSiteException(): void
    {
        $this->expectExceptionMessage(NonExistentSiteException::class);
        $this->expectExceptionMessage('Site S4 is inactive, import disabled!');

        $entityMgr = $this->getMockObjectManager();
        $converter = new SiteConverter($entityMgr);

        $converter->__invoke('S4');
    }

    private function getMockObjectManager(): MockObject
    {
        $obj  = $this->getObjects();
        $repo = $this->createMock(SiteRepository::class);
        $em = $this->createMock(ObjectManager::class);

        $repo->expects($this->once())
            ->method('getChain')
            ->willReturn($obj);

        $em->expects($this->once())
            ->method('getRepository')
            ->with('NSSentinelBundle:Site')
            ->willReturn($repo);

        return $em;
    }

    private function getObjects(): array
    {
        $region = new Region();
        $region->setId('RName');
        $region->setName('Region Name');

        $country = new Country();
        $country->setId('CName');
        $country->setName('Country Name');
        $country->setRegion($region);

        $site1 = new Site();
        $site1->setId('S1');
        $site1->setName('Site 1');
        $site1->setCountry($country);

        $site2 = new Site();
        $site2->setId('S2');
        $site2->setName('Site 2');
        $site2->setCountry($country);

        $site3 = new Site();
        $site3->setId('S3');
        $site3->setName('Site 3');
        $site3->setCountry($country);

        $site4 = new Site();
        $site4->setId('S4');
        $site4->setName('Site 5');
        $site4->setCountry($country);
        $site4->setActive(false);

        return [$site1->getCode() => $site1, $site2->getCode() => $site2, $site3->getCode() => $site3, $site4->getCode()=>$site4];
    }
}
