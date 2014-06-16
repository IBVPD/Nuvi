<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class PCVType extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\PCVType';

    public function getName()
    {
        return 'PCVType';
    }   
}

