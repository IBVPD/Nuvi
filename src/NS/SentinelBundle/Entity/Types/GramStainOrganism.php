<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class GramStainOrganism
 * @package NS\SentinelBundle\Entity\Types
 */
class GramStainOrganism extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GramStainOrganism';

    /**
     * @return string
     */
    public function getName()
    {
        return 'GramStainOrganism';
    }   
}

