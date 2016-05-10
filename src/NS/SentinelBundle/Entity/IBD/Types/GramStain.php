<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class GramStain
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class GramStain extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\GramStain';

    /**
     * @return string
     */
    public function getName()
    {
        return 'GramStain';
    }
}
