<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

class ElisaResult extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\ElisaResult';

    public function getName()
    {
        return 'ElisaResult';
    }   
}

