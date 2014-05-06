<?php

namespace NS\SentinelBundle\Form\Filters;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of Country
 *
 * @author gnat
 */
class Country extends BaseObject
{
    public function __construct(ObjectManager $em, $class = null)
    {
        $this->em    = $em;
        $this->class = $class;
    }

    public function getName()
    {
        return 'country';
    }
}
