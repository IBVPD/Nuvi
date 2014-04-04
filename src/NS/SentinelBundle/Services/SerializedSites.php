<?php

namespace NS\SentinelBundle\Services;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of SerializedSites
 *
 * @author gnat
 */
class SerializedSites
{
    private $sites;
    private $em;

    public function __construct(Session $session, ObjectManager $em)
    {
        $sites = unserialize($session->get('sites'));

        if(!$sites || count($sites) == 0) // empty session site array so build and store
        {
            $sites = $em->getRepository('NS\SentinelBundle\Entity\Site')->getChain();

            $session->set('sites',serialize($sites));
        }

        $this->sites = $sites;
        $this->em    = $em;
    }

    public function hasMultipleSites()
    {
        return (count($this->sites) > 1);
    }

    public function getSites()
    {
        return $this->sites;
    }

    public function getSite($managed = false)
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
