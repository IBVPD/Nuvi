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
        $site  = null;

        if(!empty($sites))
        {
            $sCount = count($sites);
            if($sCount > 1)
            {
                foreach($sites as $s)
                {
                    if($s->getId() == $id)
                        return $site;
                }
            }
            else if($sCount == 1 && $sites[0]->getId() == $id)
                return $sites[0];
        }

        $site  = $this->em->getRepository('NSSentinelBundle:Site')->getChains($id);
        $sites = (!empty($sites)) ? array_merge ($sites,$site) : $site;
        
        $this->session->set('sites',$sites);

        return array_pop($site);
    }
}
