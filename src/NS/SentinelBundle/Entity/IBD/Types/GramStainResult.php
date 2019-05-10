<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult as FormType;

class GramStainResult extends ArrayChoice
{
    /** @var string */
    protected $convert_class = FormType::class;

    public function getName(): string
    {
        return 'GramStainResult';
    }
}
