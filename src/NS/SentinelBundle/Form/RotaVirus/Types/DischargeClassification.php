<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class DischargeClassification extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        CONFIRMED = 1,
        DISCARDED = 2,
        INADEQUATE = 3,
        UNKNOWN = 99;

    protected $values = [
        self::CONFIRMED => 'Confirmed',
        self::DISCARDED => 'Discarded',
        self::INADEQUATE => 'Inadequately Investigated',
        self::UNKNOWN => 'Unknown',
    ];
}
