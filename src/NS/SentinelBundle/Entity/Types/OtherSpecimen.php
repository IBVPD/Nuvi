<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class OtherSpecimen extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\OtherSpecimen';

    public function getName()
    {
        return 'OtherSpecimen';
    }   
}

