<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CXRAdditionalResult
 * TODO remove once all live and ibd_cases table has been deleted
 *
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
