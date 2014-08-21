<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class IBDIntenseSupport extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IBDIntenseSupport';

    public function getName()
    {
        return 'IBDIntenseSupport';
    }   
}

