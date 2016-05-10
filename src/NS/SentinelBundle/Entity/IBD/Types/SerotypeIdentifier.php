<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class SerotypeIdentifier
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class SerotypeIdentifier extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier';

    /**
     * @return string
     */
    public function getName()
    {
        return 'SerotypeIdentifier';
    }
}
