<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class MeningitisCaseStatus extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\MeningitisCaseStatus';

    public function getName()
    {
        return 'MeningitisCaseStatus';
    }   
}

