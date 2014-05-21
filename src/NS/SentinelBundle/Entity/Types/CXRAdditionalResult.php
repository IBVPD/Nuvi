<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class CXRAdditionalResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\CXRAdditionalResult';

    public function getName()
    {
        return 'CXRAdditionalResult';
    }   
}

