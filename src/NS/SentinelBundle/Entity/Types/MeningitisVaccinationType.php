<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class MeningitisVaccinationType extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\MeningitisVaccinationType';

    public function getName()
    {
        return 'MeningitisVaccinationType';
    }   
}

