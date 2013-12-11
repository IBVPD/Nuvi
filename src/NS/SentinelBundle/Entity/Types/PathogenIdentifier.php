<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class PathogenIdentifier extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\PathogenIdentifier';

    public function getName()
    {
        return 'PathogenIdentifier';
    }   
}

