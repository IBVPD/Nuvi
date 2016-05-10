<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class PathogenIdentifier
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class PathogenIdentifier extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier';

    /**
     * @return string
     */
    public function getName()
    {
        return 'PathogenIdentifier';
    }
}
