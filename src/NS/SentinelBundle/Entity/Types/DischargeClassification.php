<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class DischargeClassification extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\DischargeClassification';

    public function getName()
    {
        return 'DischargeClassification';
    }   
}

