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
class Site implements NamedValueConverterInterface
{
    private $sites;

    public function __construct(ObjectManager $em)
    {
        $this->sites = $em->getRepository('NSSentinelBundle:Site')->getChain();
    }

    public function convert($input)
    {
        if(!isset($this->sites[$input]))
            throw new NonExistentSite("Unable to find site chain for $input");

        return $this->sites[$input];
    }

    public function getName()
    {
        return 'Site';
    }
}
