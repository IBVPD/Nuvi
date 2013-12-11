<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class PCRResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\PCRResult';

    public function getName()
    {
        return 'PCRResult';
    }   
}

