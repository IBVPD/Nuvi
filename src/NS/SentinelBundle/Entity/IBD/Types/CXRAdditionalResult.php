<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CXRAdditionalResult
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class CXRAdditionalResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CXRAdditionalResult';
    }
}
