<?php

namespace NS\SentinelBundle\Form\Types;

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

    protected $values = array(
        self::SPN          => 'Spn',
        self::HI           => 'Hi',
        self::NM           => 'Nm',
        self::NEG          => 'Neg',
        self::INCONCLUSIVE => 'Inconclusive',
        self::NOT_DONE     => 'Not Done',
    );

    public function getName()
    {
        return 'FinalResult';
    }

}
