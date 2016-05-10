<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class DischargeClassification
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class DischargeClassification extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\DischargeClassification';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IBDDischargeClassification';
    }
}
