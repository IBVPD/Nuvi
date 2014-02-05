<?php

namespace NS\SentinelBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of IdToReference
 *
 * @author gnat
 */
class IdToReference implements DataTransformerInterface
{
    private $em;

    private $session;

    public function __construct(ObjectManager $em, Session $session)
    {
        $this->em      = $em;
        $this->session = $session;

        return $this;
    }

    public function reverseTransform($value)
    {
        if(is_object($value) && $value instanceof \NS\SentinelBundle\Entity\Site)
            return $value->getId();
        
        return null;
    }

    public function transform($id)
    {
        if (null === $id)
            return "";

        $sites = $this->session->get('sites',array());
        $site  = isset($sites[$id]) ? $site:null;
        
        if(!$em->contains($site))
        {
            $uow = $em->getUnitOfWork();
            $c = $site->getCountry();
            $r = $c->getRegion();

            $uow->registerManaged($site,array('id'=>$site->getId()),array('id'=>$site->getId(),'code'=>$site->getCode()));
            $uow->registerManaged($c,array('id'=>$c->getId()),array('id'=>$c->getId(),'code'=>$c->getCode()));
            $uow->registerManaged($r,array('id'=>$r->getId()),array('id'=>$r->getId(),'code'=>$r->getCode()));
        }

        return $site;
    }
}
