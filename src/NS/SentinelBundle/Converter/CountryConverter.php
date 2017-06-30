<?php

namespace NS\SentinelBundle\Converter;

use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Exceptions\NonExistentCountryException;

class CountryConverter extends AbstractBaseObjectConverter
{
    /**
     * @param $input
     * @return object
     */
    public function __invoke($input)
    {
        $res = $this->findObject($input);
        if (!$res) {
            throw new NonExistentCountryException("Unable to find country for $input");
        } elseif (!$res->isActive()) {
            throw new NonExistentCountryException(sprintf('Country %s is inactive, import disabled!', $input));
        }

        return $res;
    }

    /**
     * Initializes the site list
     */
    public function initialize()
    {
        return $this->entityMgr->getRepository('NSSentinelBundle:Country')->getChain(null, true);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'Country';
    }

    public function getType()
    {
        return $this->getName();
    }
}
