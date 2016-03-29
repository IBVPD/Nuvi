<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class RotavirusVaccinationReceived
 * @package NS\SentinelBundle\Entity\Types
 */
class RotavirusVaccinationReceived extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived';

    /**
     * @return string
     */
    public function getName()
    {
        return 'RotavirusVaccinationReceived';
    }
}
