<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class SurveillanceConducted extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\SurveillanceConducted';

    public function getName()
    {
        return 'SurveillanceConducted';
    }   
}

