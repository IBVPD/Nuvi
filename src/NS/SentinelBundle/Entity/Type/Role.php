<?php

namespace NS\SentinelBundle\Entity\Type;

use NS\UtilBundle\Entity\Types\ArrayChoice;

class Role extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Type\Role';

    public function getName()
    {
        return 'Role';
    }   
}