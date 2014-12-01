<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class SampleType extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\SampleType';

    public function getName()
    {
        return 'SampleType';
    }   
}

