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
    private $em;
    private $repo;

    public function __construct(ObjectManager $em)
    {
        $this->em   = $em;
        $this->repo = $this->em->getRepository('NSSentinelBundle:Site');
    }

    public function convert($input)
    {
        $r = $this->repo->getChainByCode($input);

        if($r && is_array($r) && count($r) == 1)
            return array_pop($r);

        throw new NonExistentSite("Unable to find site chain for $input");
    }

    public function getName()
    {
        return 'Site';
    }
}
