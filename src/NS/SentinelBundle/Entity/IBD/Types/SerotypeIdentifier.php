<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;
use NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier as FormType;

class SerotypeIdentifier extends ArrayChoice
{
    /** @var string */
    protected $convert_class = FormType::class;

    public function getName(): string
    {
        return 'SerotypeIdentifier';
    }
}
