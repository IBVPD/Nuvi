<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class IBDCaseResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IBDCaseResult';

    public function getName()
    {
        return 'IBDCaseResult';
    }   
}

