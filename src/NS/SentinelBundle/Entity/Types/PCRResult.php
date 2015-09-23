<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class PCRResult
 * @package NS\SentinelBundle\Entity\Types
 */
class PCRResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\PCRResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'PCRResult';
    }   
}

