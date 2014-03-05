<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of MeningitisVaccinationType
 *
 */
class MeningitisVaccinationType extends ArrayChoice
{
    const UNKNOWN = 99;


    protected $values = array(
                                self::UNKNOWN => 'Unknown',
                             );

    public function getName()
    {
        return 'MeningitisVaccinationType';
    }
}
