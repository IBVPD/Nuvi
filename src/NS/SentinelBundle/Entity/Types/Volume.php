<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class Volume extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\Volume';

    public function getName()
    {
        return 'Volume';
    }   
}

