<?php

namespace NS\SentinelBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Description of SerializedSites
 *
 * @author gnat
 */
class SerializedSites implements SerializedSitesInterface
{
    /**
     * @var array
     */
    private $sites;

    /**
     * @var ObjectManager
     */
    private $entityMgr;

    /**
     * @var bool
     */
    private $isInitialized = false;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     *
     * @param SessionInterface $session
     * @param ObjectManager $entityMgr
     */
    public function __construct(SessionInterface $session, ObjectManager $entityMgr)
    {
        $this->session   = $session;
        $this->entityMgr = $entityMgr;
    }

    /**
     *
     * @return boolean
     */
    public function hasMultipleSites()
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        return (count($this->sites) > 1);
    }

    /**
     *
     * @return array
     */
    public function getSites()
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        return $this->sites;
    }

    /**
     *
     * @param boolean $managed
     * @return Site
     */
    public function getSite($managed = true)
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $site = current($this->sites);

        if ($managed && $site instanceof Site && !$this->entityMgr->contains($site)) {
            $this->registerSite($site);
        }

        return $site;
    }

    public function initialize(): void
    {
        if ($this->isInitialized) {/* || !$this->session->isStarted() - Used to be required to pass behat/phpunit tests but breaks the API stateless access */
            return;
        }

        $sites = unserialize($this->session->get('sites'),[Site::class,Region::class,Country::class]);

        if (!$sites || count($sites) === 0) { // empty session site array so build and store
            $sites = $this->populateSiteArray();
            $this->session->set('sites', serialize($sites));
        }

        $this->sites         = $sites;
        $this->isInitialized = true;
    }

    public function registerSite(Site $site): void
    {
        $uow     = $this->entityMgr->getUnitOfWork();
        $country = $site->getCountry();
        $region  = $country->getRegion();

        $uow->registerManaged($site,    ['code' => $site->getCode()], ['code' => $site->getCode()]);
        $uow->registerManaged($country, ['code' => $country->getCode()], ['code' => $country->getCode()]);
        $uow->registerManaged($region,  ['code' => $region->getCode()], ['code' => $region->getCode()]);
    }

    /**
     * @return Site[]
     */
    private function populateSiteArray(): array
    {
        $sites = [];
        foreach ($this->entityMgr->getRepository('NS\SentinelBundle\Entity\Site')->getChain() as $site) {
            $region  = new Region($site->getCountry()->getRegion()->getCode(), $site->getCountry()->getRegion()->getName());
            $country = new Country($site->getCountry()->getCode(), $site->getCountry()->getName());
            $country->setLanguage($site->getCountry()->getLanguage());
            $country->setRegion($region);

            $newSite = new Site($site->getCode(), $site->getName());
            $newSite->setCountry($country);

            $sites[] = $newSite;
        }

        return $sites;
    }
}
