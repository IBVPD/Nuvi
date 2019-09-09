<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;
use NS\SentinelBundle\Form\Types\DiarrheaLevel as BaseType;

class DiarrheaLevel extends ArrayChoice
{
    protected $convert_class = BaseType::class;

    public function getName(): string
    {
        return 'DiarrheaLevel';
    }   
}

