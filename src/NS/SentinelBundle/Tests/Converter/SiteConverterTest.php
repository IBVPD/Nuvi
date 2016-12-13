<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Converter\SiteConverter;

/**
 * Description of SiteTest
 *
 * @author gnat
 */
class SiteConverterTest extends \PHPUnit_Framework_TestCase
{

    public function testSiteConverter()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new SiteConverter($entityMgr);

        $convertedObj = $converter->__invoke('S1');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Site', $convertedObj);
        $this->assertEquals('S1', $convertedObj->getCode());

        $convertedObj = $converter->__invoke('S2');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Site', $convertedObj);
        $this->assertEquals('S2', $convertedObj->getCode());

        $this->assertEquals('Site', $converter->getName());
    }

    /**
     * @expectedException \NS\SentinelBundle\Exceptions\NonExistentSiteException
     * @expectedExceptionMessage Unable to find site for S5
     */
    public function testSiteConverterNonExistentSiteException()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new SiteConverter($entityMgr);

        $converter->__invoke('S5');
    }

    /**
     * @expectedException \NS\SentinelBundle\Exceptions\NonExistentSiteException
     * @expectedExceptionMessage Site S4 is inactive, import disabled!
     */
    public function testSiteConverterInactiveSiteException()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new SiteConverter($entityMgr);

        $converter->__invoke('S4');
    }

    private function getMockObjectManager()
    {
        $obj  = $this->getObjects();
        $repo = $this->createMock('NS\SentinelBundle\Repository\SiteRepository');
        $em = $this->createMock('Doctrine\Common\Persistence\ObjectManager');

        $repo->expects($this->once())
            ->method('getChain')
            ->willReturn($obj);

        $em->expects($this->once())
            ->method('getRepository')
            ->with('NSSentinelBundle:Site')
            ->will($this->returnValue($repo));

        return $em;
    }

    private function getObjects()
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
