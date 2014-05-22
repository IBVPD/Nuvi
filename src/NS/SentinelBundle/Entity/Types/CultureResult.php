<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class CultureResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\CultureResult';

    public function getName()
    {
        return 'CultureResult';
    }   
}

