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
    public const
        NO          = 0,
        YES_HISTORY = 1,
        YES_CARD    = 2,
        UNKNOWN     = 99;

    protected $values = [
        self::NO => 'No',
        self::YES_HISTORY => 'Yes-By-History',
        self::YES_CARD => 'Yes-By-Card',
        self::UNKNOWN => 'Unknown',
    ];
}
