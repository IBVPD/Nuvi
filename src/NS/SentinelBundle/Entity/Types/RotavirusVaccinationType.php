<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class RotavirusVaccinationType
 * @package NS\SentinelBundle\Entity\Types
 */
class RotavirusVaccinationType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\RotavirusVaccinationType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'RotavirusVaccinationType';
    }   
}

