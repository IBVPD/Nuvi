<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class HiSerotype
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class HiSerotype extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\HiSerotype';

    /**
     * @return string
     */
    public function getName()
    {
        return 'HiSerotype';
    }
}
