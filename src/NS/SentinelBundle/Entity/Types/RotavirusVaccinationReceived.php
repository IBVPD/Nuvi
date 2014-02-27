<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class RotavirusVaccinationReceived extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived';

    public function getName()
    {
        return 'RotavirusVaccinationReceived';
    }   
}

