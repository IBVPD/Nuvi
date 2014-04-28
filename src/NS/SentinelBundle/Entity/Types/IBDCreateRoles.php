<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class IBDCreateRoles extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IBDCreateRoles';

    public function getName()
    {
        return 'IBDCreateRoles';
    }   
}

