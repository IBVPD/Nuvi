<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class GAVIEligible extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Type\GAVIEligible';

    public function getName()
    {
        return 'GAVIEligible';
    }   
}

