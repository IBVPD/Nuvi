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
    public function __construct(ObjectManager $entityMgr, $class = null)
    {
        $this->entityMgr = $entityMgr;
        $this->class     = $class;
    }

    public function getName()
    {
        return 'country';
    }
}
