<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class VaccinationReceived extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\VaccinationReceived';

    public function getName()
    {
        return 'MeningitisVaccinationReceived';
    }   
}

