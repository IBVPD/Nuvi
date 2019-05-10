<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen as FormType;

class OtherSpecimen extends ArrayChoice
{
    /** @var string */
    protected $convert_class = FormType::class;

    public function getName(): string
    {
        return 'OtherSpecimen';
    }
}
