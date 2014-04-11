<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class CaseStatus extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\CaseStatus';

    public function getName()
    {
        return 'CaseStatus';
    }   
}

