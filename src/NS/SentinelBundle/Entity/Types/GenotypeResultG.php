<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class GenotypeResultG
 * @package NS\SentinelBundle\Entity\Types
 */
class GenotypeResultG extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GenotypeResultG';

    /**
     * @return string
     */
    public function getName()
    {
        return 'GenotypeResultG';
    }   
}

