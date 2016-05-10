<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class DischargeOutcome
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class DischargeOutcome extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\DischargeOutcome';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IBDDischargeOutcome';
    }
}
