<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class DischargeOutcome extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\DischargeOutcome';

    public function getName()
    {
        return 'DischargeOutcome';
    }   
}

