<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class RotavirusDischargeOutcome extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome';

    public function getName()
    {
        return 'RotavirusDischargeOutcome';
    }   
}

