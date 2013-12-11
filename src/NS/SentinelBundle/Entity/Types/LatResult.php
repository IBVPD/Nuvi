<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class LatResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\LatResult';

    public function getName()
    {
        return 'LatResult';
    }   
}

