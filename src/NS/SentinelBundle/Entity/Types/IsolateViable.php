<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

class IsolateViable extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IsolateViable';

    public function getName()
    {
        return 'IsolateViable';
    }   
}

