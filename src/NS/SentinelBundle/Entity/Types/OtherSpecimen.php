<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class OtherSpecimen
 * @package NS\SentinelBundle\Entity\Types
 */
class OtherSpecimen extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\OtherSpecimen';

    /**
     * @return string
     */
    public function getName()
    {
        return 'OtherSpecimen';
    }
}
