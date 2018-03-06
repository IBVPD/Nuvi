<?php

namespace NS\SentinelBundle\Entity\Pneumonia\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CXRAdditionalResult
 * @package NS\SentinelBundle\Entity\Pneumonia\Types
 */
class CXRAdditionalResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Pneumonia\Types\CXRAdditionalResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CXRAdditionalResult';
    }
}
