<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class EIAResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\EIAResult';

    public function getName()
    {
        return 'EIAResult';
    }   
}

