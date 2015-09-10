<?php

namespace NS\SentinelBundle\Converter;

use \Doctrine\Common\Persistence\ObjectManager;
use \NS\ImportBundle\Converter\NamedValueConverterInterface;
use \NS\SentinelBundle\Exceptions\NonExistentSite;

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

    /**
     *
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     *
     * @param string $input
     * @return Site
     * @throws NonExistentSite
     */
    public function __invoke($input)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        if (!isset($this->sites[$input])) {
            throw new NonExistentSite("Unable to find site chain for $input");
        }

        return $this->sites[$input];
    }

    /**
     * Initializes the site list
     */
    public function initialize()
    {
        $this->sites       = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getChain();
        $this->initialized = true;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'Site';
    }
}
