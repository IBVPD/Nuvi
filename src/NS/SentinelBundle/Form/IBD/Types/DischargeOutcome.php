<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class DischargeOutcome extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        DISCHARGED_ALIVE_WITHOUT_SEQUELAE = 1,
        DISCHARGED_ALIVE_WITH_SEQUELAE    = 2,
        DIED                              = 3,
        TRANSFERRED                       = 4,
        LEFT_AGAINST_ADVICE               = 5,
        UNKNOWN                           = 99;

    protected $values = [
        self::DISCHARGED_ALIVE_WITHOUT_SEQUELAE => 'Discharged alive, without sequelae',
        self::DISCHARGED_ALIVE_WITH_SEQUELAE => 'Discharged alive, with sequelae',
        self::DIED => 'Died',
        self::TRANSFERRED => 'Transferred',
        self::LEFT_AGAINST_ADVICE => 'Left/Discharged against medical advice',
        self::UNKNOWN => 'Unknown',
    ];
}
