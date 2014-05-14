<?php

namespace NS\SentinelBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
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
    private $em;

    public function __construct(Session $session, ObjectManager $em)
    {
        $sites = unserialize($session->get('sites'));

        if(!$sites || count($sites) == 0) // empty session site array so build and store
        {
            $sites = array();

            foreach($em->getRepository('NS\SentinelBundle\Entity\Site')->getChain() as $site)
            {
                $r = new Region();
                $r->setName($site->getCountry()->getRegion()->getName());
                $r->setId($site->getCountry()->getRegion()->getId());
                $r->setCode($site->getCountry()->getRegion()->getcode());

                $c = new Country();
                $c->setId($site->getCountry()->getName());
                $c->setId($site->getCountry()->getId());
                $c->setCode($site->getcountry()->getcode());
                $c->setRegion($r);

                $s = new Site();
                $s->setId($site->getId());
                $s->setName($site->getName());
                $s->setCode($site->getCode());
                $s->setCountry($c);

                $sites[] = $s;
            }

            $session->set('sites',serialize($sites));
        }

        $this->sites = $sites;
        $this->em    = $em;
    }

    public function hasMultipleSites()
    {
        return (count($this->sites) > 1);
    }

    public function setSites(array $sites)
    {
        $this->sites = $sites;
    }

    public function getSites()
    {
        return $this->sites;
    }

    public function getSite($managed = true)
    {
        $site = current($this->sites);

        if($managed && !$this->em->contains($site))
        {
            $uow = $this->em->getUnitOfWork();
            $c   = $site->getCountry();
            $r   = $c->getRegion();

            $uow->registerManaged($site,array('id'=>$site->getId()),array('id'=>$site->getId(),'code'=>$site->getCode()));
            $uow->registerManaged($c,array('id'=>$c->getId()),array('id'=>$c->getId(),'code'=>$c->getCode()));
            $uow->registerManaged($r,array('id'=>$r->getId()),array('id'=>$r->getId(),'code'=>$r->getCode()));
        }

        return $site;
    }
}
