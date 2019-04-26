<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

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
