<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of VaccinationReceived
 *
 */
class VaccinationReceived extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NO          = 0;
    const YES_HISTORY = 1;
    const YES_CARD    = 2;
    const UNKNOWN     = 99;

    protected $values = [
                                self::NO            => 'No',
                                self::YES_HISTORY   => 'Yes-By-History',
                                self::YES_CARD      => 'Yes-By-Card',
                                self::UNKNOWN       => 'Unknown',
    ];
}
