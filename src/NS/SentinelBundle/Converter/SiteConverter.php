<?php

namespace NS\SentinelBundle\Converter;

use Doctrine\Common\Persistence\ObjectManager;
use NS\ImportBundle\Converter\NamedValueConverterInterface;
use NS\SentinelBundle\Exceptions\NonExistentSite;

/**
 * Description of Site
 *
 * @author gnat
 */
class SiteConverter implements NamedValueConverterInterface
{
    private $sites;
    private $initialized = false;
    private $entityMgr;

    public function __construct(ObjectManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    public function convert($input)
    {
        if (!$this->initialized)
        {
            $this->initialize();
        }

        if (!isset($this->sites[$input]))
        {
            throw new NonExistentSite("Unable to find site chain for $input");
        }

        return $this->sites[$input];
    }

    public function initialize()
    {
        $this->sites       = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getChain();
        $this->initialized = true;
    }

    public function getName()
    {
        return 'Site';
    }
}
