<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class HiSerotype extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\HiSerotype';

    public function getName()
    {
        return 'HiSerotype';
    }   
}

