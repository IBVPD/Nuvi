<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class RotavirusDoses extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\RotavirusDoses';

    public function getName()
    {
        return 'RotavirusDoses';
    }   
}

