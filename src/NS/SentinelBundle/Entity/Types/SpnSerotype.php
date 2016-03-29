<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class SpnSerotype
 * @package NS\SentinelBundle\Entity\Types
 */
class SpnSerotype extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\SpnSerotype';

    /**
     * @return string
     */
    public function getName()
    {
        return 'SpnSerotype';
    }
}
