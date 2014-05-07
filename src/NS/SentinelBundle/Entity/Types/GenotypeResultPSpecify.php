<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class GenotypeResultPSpecify extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GenotypeResultPSpecify';

    public function getName()
    {
        return 'GenotypeResultPSpecify';
    }   
}

