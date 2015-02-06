<?php

namespace NS\SentinelBundle\Services;

use \Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use \NS\SentinelBundle\Entity\Region;
use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Entity\Site;

/**
 * Description of SerializedSites
 *
 * @author gnat
 */
class SerializedSites implements SerializedSitesInterface
{
    private $sites;
    private $entityMgr;
    private $isInitialized = false;
    private $session;

    /**
     *
     * @param Session $session
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
        if(!$this->isInitialized)
            $this->initialize();

        return (count($this->sites) > 1);
    }

    /**
     *
     * @return array
     */
    public function getSites()
    {
        if(!$this->isInitialized)
            $this->initialize();

        return $this->sites;
    }

    /**
     *
     * @param boolean $managed
     * @return Site
     */
    public function getSite($managed = true)
    {
        if(!$this->isInitialized)
            $this->initialize();

        $site = current($this->sites);

        if ($managed && $site instanceof Site && !$this->entityMgr->contains($site))
            $this->registerSite ($site);

        return $site;
    }

    /**
     *
     * @return null
     */
    public function initialize()
    {
        if($this->isInitialized) /* || !$this->session->isStarted() - Used to be required to pass behat/phpunit tests but breaks the API stateless access */
            return;

        $sites = unserialize($this->session->get('sites'));

        if(!$sites || count($sites) == 0) // empty session site array so build and store
        {
            $sites = $this->populateSiteArray();
            $this->session->set('sites',serialize($sites));
        }

        $this->sites = $sites;
        $this->isInitialized = true;
    }

    /**
     *
     * @param Site $site
     */
    private function registerSite(Site $site)
    {
        $uow     = $this->entityMgr->getUnitOfWork();
        $country = $site->getCountry();
        $region  = $country->getRegion();

        $uow->registerManaged($site,   array('code' => $site->getCode()), array('code' => $site->getCode()));
        $uow->registerManaged($country,array('id'   => $country->getId()),array('id'   => $country->getId(), 'code' => $country->getCode()));
        $uow->registerManaged($region, array('id'   => $region->getId()), array('id'   => $region->getId(),  'code' => $region->getCode()));
    }

    /**
     *
     * @return Site
     */
    private function populateSiteArray()
    {
        $sites = array();
        foreach($this->entityMgr->getRepository('NS\SentinelBundle\Entity\Site')->getChain() as $site)
        {
            $region = new Region();
            $region->setName($site->getCountry()->getRegion()->getName());
            $region->setId($site->getCountry()->getRegion()->getId());
            $region->setCode($site->getCountry()->getRegion()->getcode());

            $country = new Country();
            $country->setId($site->getCountry()->getId());
            $country->setName($site->getCountry()->getName());
            $country->setCode($site->getCountry()->getCode());
            $country->setLanguage($site->getCountry()->getLanguage());
            $country->setRegion($region);

            $newSite = new Site();
            $newSite->setCode($site->getCode());
            $newSite->setName($site->getName());
            $newSite->setCountry($country);

            $sites[] = $newSite;
        }

        return $sites;
    }
}
