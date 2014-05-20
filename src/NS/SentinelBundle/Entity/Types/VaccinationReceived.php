<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class MeningitisVaccinationReceived extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived';

    public function getName()
    {
        return 'MeningitisVaccinationReceived';
    }   
}

