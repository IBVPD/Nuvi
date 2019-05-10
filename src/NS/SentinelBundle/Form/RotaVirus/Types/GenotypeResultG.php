<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class GenotypeResultG extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const G1 = 1;
    public const G2 = 2;
    public const G3 = 3;
    public const G4 = 4;
    public const G5 = 5;
    public const G6 = 6;
    public const G7 = 7;
    public const G8 = 8;
    public const G9 = 9;
    public const G10 = 10;
    public const G11 = 11;
    public const G12 = 12;
    public const G20 = 20;
    public const NON_TYPEABLE = 30;
    public const MIXED = 40;
    public const OTHER = 50;

    protected $values = [
        self::G1 => 'G1',
        self::G2 => 'G2',
        self::G3 => 'G3',
        self::G4 => 'G4',
        self::G5 => 'G5',
        self::G6 => 'G6',
        self::G7 => 'G7',
        self::G8 => 'G8',
        self::G9 => 'G9',
        self::G10 => 'G10',
        self::G11 => 'G11',
        self::G12 => 'G12',
        self::G20 => 'G20',
        self::NON_TYPEABLE => 'Non-typeable',
        self::MIXED => 'Mixed (specify)',
        self::OTHER => 'Other (specify)',
    ];
}
