<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class ElisaKit extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\ElisaKit';

    public function getName()
    {
        return 'ElisaKit';
    }   
}

