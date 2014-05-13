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
        $em             = $this->getMockObjectManager();
        $siteSerializer = new SerializedSites($session,$em);
        $site           = $siteSerializer->getSite();

        $this->assertTrue($siteSerializer->hasMultipleSites(),"Has multiple sites");
        $this->assertEquals($site->getId(),1);
    }

    public function testSerializedSitesHasIds()
    {
        $session        = new Session(new MockFileSessionStorage());
        $em             = $this->getMockObjectManager();
        $siteSerializer = new SerializedSites($session,$em);

        $this->assertTrue($siteSerializer->hasMultipleSites(),"Has multiple sites");
        foreach($siteSerializer->getSites() as $s)
            $this->assertGreaterThan(0,$s->getId(), "Id is greater than 0");
    }

    private function getMockObjectManager()
    {
        $obj  = $this->getObjects();
        $repo = $this->getMockBuilder('NS\SentinelBundle\Repository\Site')
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

        $s1 = new Site();
        $s1->setId(1);
        $s1->setName('Site 1');
        $s1->setCountry($country);

        $s2 = new Site();
        $s2->setId(2);
        $s2->setName('Site 2');
        $s2->setCountry($country);

        $s3 = new Site();
        $s3->setId(3);
        $s3->setName('Site 3');
        $s3->setCountry($country);

        return array($s1,$s2,$s3);
    }
}
