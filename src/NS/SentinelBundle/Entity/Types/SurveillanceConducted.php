<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class SurveillanceConducted
 * @package NS\SentinelBundle\Entity\Types
 */
class SurveillanceConducted extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\SurveillanceConducted';

    /**
     * @return string
     */
    public function getName()
    {
        return 'SurveillanceConducted';
    }   
}

