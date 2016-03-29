<?php

namespace NS\SentinelBundle\Converter;

use \Doctrine\Common\Persistence\ObjectManager;
use \NS\ImportBundle\Converter\NamedValueConverterInterface;
use \NS\SentinelBundle\Exceptions\NonExistentSiteException;

/**
 * Description of Site
 *
 * @author gnat
 */
class SiteConverter extends AbstractBaseObjectConverter
{
    public function __invoke($input)
    {
        $res = $this->findObject($input);
        if (!$res) {
            throw new NonExistentSiteException("Unable to find site for $input");
        } elseif (!$res->isActive()) {
            throw new NonExistentSiteException(sprintf('Site %s is inactive, import disabled!', $input));
        }

        return $res;
    }

    /**
     * Initializes the site list
     */
    public function initialize()
    {
        return $this->entityMgr->getRepository('NSSentinelBundle:Site')->getChain(null, true);
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
