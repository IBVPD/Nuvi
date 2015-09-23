<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class VaccinationReceived
 * @package NS\SentinelBundle\Entity\Types
 */
class VaccinationReceived extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\VaccinationReceived';

    /**
     * @return string
     */
    public function getName()
    {
        return 'VaccinationReceived';
    }   
}

