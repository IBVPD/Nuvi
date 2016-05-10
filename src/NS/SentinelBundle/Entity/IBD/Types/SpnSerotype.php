<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class SpnSerotype
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class SpnSerotype extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\SpnSerotype';

    /**
     * @return string
     */
    public function getName()
    {
        return 'SpnSerotype';
    }
}
