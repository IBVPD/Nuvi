<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IntenseSupport
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class IntenseSupport extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\IntenseSupport';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IntenseSupport';
    }
}
