<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class SampleType
 * @package NS\SentinelBundle\Entity\Types
 */
class SampleType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\SampleType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'SampleType';
    }
}
