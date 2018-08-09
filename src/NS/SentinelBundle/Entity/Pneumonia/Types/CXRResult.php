<?php

namespace NS\SentinelBundle\Entity\Pneumonia\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CXRResult
 * @package NS\SentinelBundle\Entity\Pneumonia\Types
 */
class CXRResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Pneumonia\Types\CXRResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CXRResult';
    }
}
