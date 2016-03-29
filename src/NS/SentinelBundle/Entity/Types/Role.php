<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

class Role extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Types\Role';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Role';
    }
}
