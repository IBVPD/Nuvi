<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class DischargeClassification
 * @package NS\SentinelBundle\Entity\Types
 */
class DischargeClassification extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\DischargeClassification';

    /**
     * @return string
     */
    public function getName()
    {
        return 'DischargeClassification';
    }   
}

