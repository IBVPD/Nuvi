<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class SpnSerotype extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\SpnSerotype';

    public function getName()
    {
        return 'SpnSerotype';
    }   
}

