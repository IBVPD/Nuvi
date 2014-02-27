<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class RotavirusVaccinationType extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\RotavirusVaccinationType';

    public function getName()
    {
        return 'RotavirusVaccinationType';
    }   
}

