<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class GenotypeResultP extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GenotypeResultP';

    public function getName()
    {
        return 'GenotypeResultP';
    }   
}

