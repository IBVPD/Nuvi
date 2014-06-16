<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

class DischargeDiagnosis extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\DischargeDiagnosis';

    public function getName()
    {
        return 'DischargeDiagnosis';
    }   
}

