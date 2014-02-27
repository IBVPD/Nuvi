<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class Rehydration extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\Rehydration';

    public function getName()
    {
        return 'Rehydration';
    }   
}

