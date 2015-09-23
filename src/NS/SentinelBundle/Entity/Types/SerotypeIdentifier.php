<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class SerotypeIdentifier
 * @package NS\SentinelBundle\Entity\Types
 */
class SerotypeIdentifier extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\SerotypeIdentifier';

    /**
     * @return string
     */
    public function getName()
    {
        return 'SerotypeIdentifier';
    }   
}

