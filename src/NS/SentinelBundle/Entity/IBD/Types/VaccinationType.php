<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class VaccinationType
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class VaccinationType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\VaccinationType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IBDVaccinationType';
    }
}
