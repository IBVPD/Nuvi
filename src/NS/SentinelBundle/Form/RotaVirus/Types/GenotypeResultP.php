<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of GenotypeResultP
 *
 */
class GenotypeResultP extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const P1 = 1;
    public const P2 = 2;
    public const P3 = 3;
    public const P4 = 4;
    public const P5 = 5;
    public const P6 = 6;
    public const P7 = 7;
    public const P8 = 8;
    public const P9 = 9;
    public const P10 = 10;
    public const P11 = 11;
    public const P12 = 12;
    public const P14 = 14;
    public const P19 = 19;
    public const P25 = 25;
    public const P28 = 28;
    public const NON_TYPEABLE = 30;
    public const MIXED = 40;
    public const OTHER = 50;

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
