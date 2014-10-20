<?php

namespace NS\SentinelBundle\Form\Filters;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of Region
 *
 * @author gnat
 */
class Region extends BaseObject
{
    public function __construct(ObjectManager $entityMgr, $class = null)
    {
        $this->entityMgr = $entityMgr;
        $this->class     = $class;
    }

    public function getName()
    {
        return 'region';
    }
}
