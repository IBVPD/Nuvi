<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CXRResult
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class CXRResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\CXRResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CXRResult';
    }
}
