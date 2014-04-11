<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class MeningitisCaseResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\MeningitisCaseResult';

    public function getName()
    {
        return 'MeningitisCaseResult';
    }   
}

