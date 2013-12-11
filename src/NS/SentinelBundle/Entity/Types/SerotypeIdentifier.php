<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class SerotypeIdentifier extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\SerotypeIdentifier';

    public function getName()
    {
        return 'SerotypeIdentifier';
    }   
}

