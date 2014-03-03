<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of MeningitisCaseStatus
 *
 */
class MeningitisCaseStatus extends ArrayChoice
{
    const UNKNOWN   = 0;
    const SUSPECTED = 1;
    const PROBABLE  = 2;
    const CONFIRMED = 3;

    protected $values = array(
                                self::UNKNOWN   => 'Unknown',
                                self::SUSPECTED => 'Suspected',
                                self::PROBABLE  => 'Probable',
                                self::CONFIRMED => 'Confirmed'
                             );

    public function getName()
    {
        return 'MeningitisCaseStatus';
    }
}
