<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class GramStain extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GramStain';

    public function getName()
    {
        return 'GramStain';
    }   
}

