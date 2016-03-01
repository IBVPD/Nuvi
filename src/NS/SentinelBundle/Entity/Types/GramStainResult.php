<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

class GramStainResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GramStainResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'GramStainResult';
    }
}
