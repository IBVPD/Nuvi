<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class GramStain
 * @package NS\SentinelBundle\Entity\Types
 */
class GramStain extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GramStain';

    /**
     * @return string
     */
    public function getName()
    {
        return 'GramStain';
    }   
}

