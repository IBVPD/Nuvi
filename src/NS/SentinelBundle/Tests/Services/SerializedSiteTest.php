<?php

namespace NS\SentinelBundle\Tests\Services;

use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Services\SerializedSites;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * Description of SerializedSiteTest
 *
 * @author gnat
 */
class SerializedSiteTest extends \PHPUnit_Framework_TestCase
{
    public function testSitesCanBeSerialized()
    {
        $sites           = $this->getObjects();
        $serializedSites = serialize($sites);
        $unSites         = unserialize($serializedSites);

        $this->assertEquals(count($sites),count($unSites),"We have the same number of");

        foreach($unSites as $s)
            $this->assertGreaterThan(0, $s->getId(), "We still have an id");
    }

    public function testSerializedSitesHasMultipleSites()
    {
        $session        = new Session(new MockFileSessionStorage());
        $session->start();
        $em             = $this->getMockObjectManager();
        $siteSerializer = new SerializedSites($session,$em);
        $site           = $siteSerializer->getSite();

        $this->assertTrue($siteSerializer->hasMultipleSites(),"Has multiple sites");
        $this->assertEquals($site->getId(),1);
    }

    public function testSerializedSitesHasIds()
    {
        $session        = new Session(new MockFileSessionStorage());
        $session->start();
        $em             = $this->getMockObjectManager();
        $siteSerializer = new SerializedSites($session,$em);

        $this->assertTrue($siteSerializer->hasMultipleSites(),"Has multiple sites");
        foreach($siteSerializer->getSites() as $s)
            $this->assertGreaterThan(0,$s->getId(), "Id is greater than 0");
    }

    private function getMockObjectManager()
    {
        $obj  = $this->getObjects();
        $repo = $this->getMockBuilder('NS\SentinelBundle\Repository\SiteRepository')
            ->disableOriginalConstructor()
                     ->getMock();

        $repo->expects($this->any())
             ->method('getChain')
             ->will($this->returnValue($obj));

        $em = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
                   ->disableOriginalConstructor()
                   ->getMock();

        $em->expects($this->any())
           ->method('getRepository')
           ->with('NS\SentinelBundle\Entity\Site')
           ->will($this->returnValue($repo));

        $em->expects($this->any())
           ->method('contains')
           ->will($this->returnValue(true));

        return $em;
    }

    private function getObjects()
    {
        $region = new Region();
        $region->setId(1);
        $region->setName('Region Name');

        $country = new Country();
        $country->setId(1);
        $country->setName('Country Name');
        $country->setRegion($region);

        $site1 = new Site();
        $site1->setId(1);
        $site1->setName('Site 1');
        $site1->setCountry($country);

        $site2 = new Site();
        $site2->setId(2);
        $site2->setName('Site 2');
        $site2->setCountry($country);

        $site3 = new Site();
        $site3->setId(3);
        $site3->setName('Site 3');
        $site3->setCountry($country);

        return array($site1, $site2, $site3);
    }
}
