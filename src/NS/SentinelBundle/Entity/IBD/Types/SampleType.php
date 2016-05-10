<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class SampleType
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class SampleType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\SampleType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'SampleType';
    }
}
