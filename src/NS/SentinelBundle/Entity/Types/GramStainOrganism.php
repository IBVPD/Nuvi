<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

class GramStainOrganism extends ArrayChoice
{
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GramStainOrganism';

    public function getName()
    {
        return 'GramStainOrganism';
    }
}
