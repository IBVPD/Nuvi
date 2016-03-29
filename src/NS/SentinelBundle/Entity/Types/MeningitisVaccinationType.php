<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class MeningitisVaccinationType
 * @package NS\SentinelBundle\Entity\Types
 */
class MeningitisVaccinationType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\MeningitisVaccinationType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'MeningitisVaccinationType';
    }
}
