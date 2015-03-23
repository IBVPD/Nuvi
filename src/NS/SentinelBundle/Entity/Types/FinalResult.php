<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class FinalResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\FinalResult';

    public function getName()
    {
        return 'FinalResult';
    }   
}

