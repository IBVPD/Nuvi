<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;

/**
 * Description of SiteTest
 *
 * @author gnat
 */
class SiteTest extends \PHPUnit_Framework_TestCase
{

    public function testSiteConverter()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new \NS\SentinelBundle\Converter\SiteConverter($entityMgr);

        $convertedObj = $converter->convert('S1');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Site', $convertedObj);
        $this->assertEquals('S1', $convertedObj->getCode());

        $convertedObj = $converter->convert('S2');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Site', $convertedObj);
        $this->assertEquals('S2', $convertedObj->getCode());
    }

    /**
     * @expectedException NS\SentinelBundle\Exceptions\NonExistentSite
     */
    public function testSiteConverterException()
    {
        $entityMgr = $this->getMockObjectManager();
        $converter = new \NS\SentinelBundle\Converter\SiteConverter($entityMgr);

        $converter->convert('S4');
    }

    private function getMockObjectManager()
    {
        $obj  = $this->getObjects();
        $repo = $this->getMockBuilder('NS\SentinelBundle\Repository\SiteRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $em = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

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

        return array($site1->getCode() => $site1, $site2->getCode() => $site2, $site3->getCode() => $site3);
    }

}
