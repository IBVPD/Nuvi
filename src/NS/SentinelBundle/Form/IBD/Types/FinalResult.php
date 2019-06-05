<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

class FinalResult extends ArrayChoice
{
    public const 
        SPN          = 1,
        HI           = 2,
        NM           = 3,
        NEG          = 4,
        INCONCLUSIVE = 5,
        NOT_DONE     = 6,
        SPN_HI       = 7,
        SPN_NM       = 8,
        HI_NM        = 9,
        SPN_HI_NM    = 10;

    protected $values = [
        self::SPN          => 'Spn',
        self::HI           => 'Hi',
        self::NM           => 'Nm',
        self::NEG          => 'Neg',
        self::SPN_HI       => 'Spn+Hi',
        self::SPN_NM       => 'Spn+Nm',
        self::HI_NM        => 'Hi+Nm',
        self::SPN_HI_NM    => 'Spn+Hi+Nm',
        self::INCONCLUSIVE => 'Inconclusive',
        self::NOT_DONE     => 'Not Done',
    ];
}
