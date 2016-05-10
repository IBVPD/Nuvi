<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class DischargeClassification extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification';

    public function getName()
    {
        return 'RVDischargeClassification';
    }   
}

