<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class IsolateType extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IsolateType';

    public function getName()
    {
        return 'IsolateType';
    }   
}

