<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class Dehydration extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\Dehydration';

    public function getName()
    {
        return 'Dehydration';
    }   
}

