<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class CXRResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\CXRResult';

    public function getName()
    {
        return 'CXRResult';
    }   
}

