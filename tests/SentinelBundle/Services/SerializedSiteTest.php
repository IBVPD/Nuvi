<?php

namespace NS\SentinelBundle\Tests\Services;

use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Services\SerializedSites;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Repository\SiteRepository;

/**
 * Description of SerializedSiteTest
 *
 * @author gnat
 */
class SerializedSiteTest extends TestCase
{
    public function testSitesCanBeSerialized(): void
    {
        $sites           = $this->getObjects();
        $serializedSites = serialize($sites);
        $unSites         = unserialize($serializedSites);

        $this->assertCount(count($sites), $unSites, 'We have the same number of');

        foreach ($unSites as $s) {
            $this->assertGreaterThan(0, $s->getId(), 'We still have an id');
        }
    }

    public function testSerializedSitesHasMultipleSites(): void
    {
        $session        = new Session(new MockFileSessionStorage());
        $session->start();
        $em             = $this->getMockObjectManager();
        $siteSerializer = new SerializedSites($session, $em);
        $site           = $siteSerializer->getSite();

        $this->assertTrue($siteSerializer->hasMultipleSites(), 'Has multiple sites');
        $this->assertEquals($site->getId(), 1);
    }

    public function testSerializedSitesHasIds(): void
    {
        $session        = new Session(new MockFileSessionStorage());
        $session->start();
        $em             = $this->getMockObjectManager();
        $siteSerializer = new SerializedSites($session, $em);

        $this->assertTrue($siteSerializer->hasMultipleSites(), 'Has multiple sites');
        foreach ($siteSerializer->getSites() as $s) {
            $this->assertGreaterThan(0, $s->getId(), 'Id is greater than 0');
        }
    }

    private function getMockObjectManager(): MockObject
    {
        $obj  = $this->getObjects();
        $repo = $this->createMock(SiteRepository::class);

        $repo
             ->method('getChain')
             ->willReturn($obj);

        $em = $this->createMock(ObjectManager::class);

        $em
           ->method('getRepository')
           ->with(Site::class)
           ->willReturn($repo);

        $em
           ->method('contains')
           ->willReturn(true);

        return $em;
    }

    private function getObjects(): array
    {
        $region = new Region(1, 'Region Name');

        $country = new Country(1, 'Country Name');
        $country->setRegion($region);

        $site1 = new Site(1, 'Site 1');
        $site1->setCountry($country);

        $site2 = new Site(2, 'Site 2');
        $site2->setCountry($country);

        $site3 = new Site(3, 'Site 3');
        $site3->setCountry($country);

        return [$site1, $site2, $site3];
    }

    public function testRegisterSite(): void
    {
        $region = new Region('rCode', 'RegionName');
        $country = new Country('cCode', 'CountryName');
        $country->setRegion($region);
        $site = new Site('sId', 'SiteName');
        $site->setCountry($country);

        $mockUoW = $this->createMock(UnitOfWork::class);

        $mockUoW->expects($this->at(0))
            ->method('registerManaged')
            ->with($site,   ['code' => 'sId'], ['code' => 'sId']);

        $mockUoW->expects($this->at(1))
            ->method('registerManaged')
            ->with($country, ['code' => 'cCode'], ['code'=>'cCode']);

        $mockUoW->expects($this->at(2))
            ->method('registerManaged')
            ->with($region, ['code' => 'rCode'], ['code'=>'rCode']);

        $mockEntityMgr = $this->createMock(EntityManager::class);

        $mockEntityMgr->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($mockUoW);

        $session        = new Session(new MockFileSessionStorage());
        $session->start();

        $serializedSites = new SerializedSites($session, $mockEntityMgr);
        $serializedSites->registerSite($site);
    }
}
