<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class LatResult
 * @package NS\SentinelBundle\Entity\Types
 */
class LatResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\LatResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'LatResult';
    }
}
