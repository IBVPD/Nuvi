<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class GenotypeResultP extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        P1 = 1,
        P2 = 2,
        P3 = 3,
        P4 = 4,
        P5 = 5,
        P6 = 6,
        P7 = 7,
        P8 = 8,
        P9 = 9,
        P10 = 10,
        P11 = 11,
        P12 = 12,
        P14 = 14,
        P19 = 19,
        P25 = 25,
        P28 = 28,
        NON_TYPEABLE = 30,
        MIXED = 40,
        OTHER = 50;

    protected $values = [
        self::P1 => 'P[1]',
        self::P2 => 'P[2]',
        self::P3 => 'P[3]',
        self::P4 => 'P[4]',
        self::P5 => 'P[5]',
        self::P6 => 'P[6]',
        self::P7 => 'P[7]',
        self::P8 => 'P[8]',
        self::P9 => 'P[9]',
        self::P10 => 'P[10]',
        self::P11 => 'P[11]',
        self::P12 => 'P[12]',
        self::P14 => 'P[14]',
        self::P19 => 'P[19]',
        self::P25 => 'P[25]',
        self::P28 => 'P[28]',
        self::NON_TYPEABLE => 'Non-typeable',
        self::MIXED => 'Mixed (specify)',
        self::OTHER => 'Other (specify)',
    ];
}
