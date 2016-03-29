<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class HiSerotype
 * @package NS\SentinelBundle\Entity\Types
 */
class HiSerotype extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\HiSerotype';

    /**
     * @return string
     */
    public function getName()
    {
        return 'HiSerotype';
    }
}
