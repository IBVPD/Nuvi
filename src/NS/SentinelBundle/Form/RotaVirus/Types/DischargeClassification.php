<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of RotaVirusDischargeClassification
 *
 */
class DischargeClassification  extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const CONFIRMED  = 1;
    const DISCARDED  = 2;
    const INADEQUATE = 3;
    const UNKNOWN    = 99;

    protected $values = [
                                self::CONFIRMED => 'Confirmed',
                                self::DISCARDED => 'Discarded',
                                self::INADEQUATE=> 'Inadequately Investigated',
                                self::UNKNOWN   => 'Unknown',
    ];
}
