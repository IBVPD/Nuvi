<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CXRResult
 * @package NS\SentinelBundle\Entity\Types
 */
class CXRResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\CXRResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CXRResult';
    }   
}

