<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of FinalResult
 *
 */
class FinalResult extends ArrayChoice
{
    const SPN          = 1;
    const HI           = 2;
    const NM           = 3;
    const NEG          = 4;
    const INCONCLUSIVE = 5;
    const NOT_DONE     = 6;
    const SPN_HI       = 7;
    const SPN_NM       = 8;
    const HI_NM        = 9;
    const SPN_HI_NM    = 10;

    protected $values = array(
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
    );
}
