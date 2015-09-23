<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class GenotypeResultP
 * @package NS\SentinelBundle\Entity\Types
 */
class GenotypeResultP extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\GenotypeResultP';

    /**
     * @return string
     */
    public function getName()
    {
        return 'GenotypeResultP';
    }   
}

