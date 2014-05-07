<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class PCRVP6Result extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\PCRVP6Result';

    public function getName()
    {
        return 'PCRVP6Result';
    }   
}

