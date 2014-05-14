<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class NmSerogroup extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\NmSerogroup';

    public function getName()
    {
        return 'NmSerogroup';
    }   
}

