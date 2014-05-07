<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class GenotypeResultGSpecify extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GenotypeResultGSpecify';

    public function getName()
    {
        return 'GenotypeResultGSpecify';
    }   
}

