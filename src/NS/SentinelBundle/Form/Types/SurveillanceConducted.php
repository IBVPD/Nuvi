<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of SurveillanceConducted
 *
 */
class SurveillanceConducted extends ArrayChoice
{
    public const IBD  = 1;
    public const ROTA = 2;
    public const BOTH = 3;

    protected $values = [
        self::IBD => 'IBD',
        self::ROTA => 'Rota',
        self::BOTH => 'Both',
    ];
}
