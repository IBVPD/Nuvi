<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class Dehydration extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        NONE = 0,
        SEVERE = 1,
        SOME = 2,
        MODERATE = 3,
        UNKNOWN = 99;

    protected $values = [
        self::NONE => 'None',
        self::SEVERE => 'Severe',
        self::MODERATE => 'Moderate',
        self::SOME => 'Some',
        self::UNKNOWN => 'Unknown',
    ];
}
