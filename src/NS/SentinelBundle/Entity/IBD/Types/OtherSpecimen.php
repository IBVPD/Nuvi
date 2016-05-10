<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class OtherSpecimen
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class OtherSpecimen extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\OtherSpecimen';

    /**
     * @return string
     */
    public function getName()
    {
        return 'OtherSpecimen';
    }
}
