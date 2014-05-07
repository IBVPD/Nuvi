<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class GenotypeResultG extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GenotypeResultG';

    public function getName()
    {
        return 'GenotypeResultG';
    }   
}

