<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of SurveillanceConducted
 *
 */
class SurveillanceConducted extends ArrayChoice
{
    const IBD  = 1;
    const ROTA = 2;
    const BOTH = 3;

    protected $values = [
                                self::IBD  => 'IBD',
                                self::ROTA => 'Rota',
                                self::BOTH => 'Both',
    ];
}
