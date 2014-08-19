<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class ThreeDoses extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\ThreeDoses';

    public function getName()
    {
        return 'ThreeDoses';
    }   
}

