<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class FinalResult extends ArrayChoice
{
    protected $convert_class = 'NSSentinelBundle\Form\Types\FinalResult';

    public function getName()
    {
        return 'FinalResult';
    }   
}

