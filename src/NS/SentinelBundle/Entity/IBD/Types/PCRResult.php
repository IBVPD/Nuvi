<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class PCRResult
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class PCRResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\PCRResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'PCRResult';
    }
}
