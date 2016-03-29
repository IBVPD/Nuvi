<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class PathogenIdentifier
 * @package NS\SentinelBundle\Entity\Types
 */
class PathogenIdentifier extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\PathogenIdentifier';

    /**
     * @return string
     */
    public function getName()
    {
        return 'PathogenIdentifier';
    }
}
