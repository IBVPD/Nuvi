<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of FinalResult
 *
 */
class FinalResult extends ArrayChoice
{
    public const SPN          = 1;
    public const HI           = 2;
    public const NM           = 3;
    public const NEG          = 4;
    public const INCONCLUSIVE = 5;
    public const NOT_DONE     = 6;
    public const SPN_HI       = 7;
    public const SPN_NM       = 8;
    public const HI_NM        = 9;
    public const SPN_HI_NM    = 10;

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
