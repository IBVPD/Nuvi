<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class RotavirusDischargeOutcome
 * @package NS\SentinelBundle\Entity\RotaVirus\Types
 */
class DischargeOutcome extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome';

    /**
     * @return string
     */
    public function getName()
    {
        return 'RVDischargeOutcome';
    }
}
