<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class LatResult
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class LatResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\LatResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'LatResult';
    }
}
