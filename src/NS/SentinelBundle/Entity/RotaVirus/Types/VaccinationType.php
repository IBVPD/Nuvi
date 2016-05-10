<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class VaccinationType
 * @package NS\SentinelBundle\Entity\RotaVirus\Types
 */
class VaccinationType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\VaccinationType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'RVVaccinationType';
    }
}
