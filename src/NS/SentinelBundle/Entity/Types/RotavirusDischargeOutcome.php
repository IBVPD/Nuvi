<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class RotavirusDischargeOutcome
 * @package NS\SentinelBundle\Entity\Types
 */
class RotavirusDischargeOutcome extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome';

    /**
     * @return string
     */
    public function getName()
    {
        return 'RotavirusDischargeOutcome';
    }
}
