<?php

namespace NS\SentinelBundle\Form\Filters;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of Site
 *
 * @author gnat
 */
class Site extends BaseObject
{
    public function __construct(ObjectManager $em, $class = null)
    {
        $this->em    = $em;
        $this->class = $class;
    }

    public function getName()
    {
        return 'site';
    }
}
