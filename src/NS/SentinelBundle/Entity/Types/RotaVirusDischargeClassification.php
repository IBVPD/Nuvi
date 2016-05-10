<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class RotaVirusDischargeClassification extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\RotaVirusDischargeClassification';

    public function getName()
    {
        return 'RotaVirusDischargeClassification';
    }   
}

