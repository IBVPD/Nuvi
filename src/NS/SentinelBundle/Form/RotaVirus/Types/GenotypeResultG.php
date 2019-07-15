<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class GenotypeResultG extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        G1 = 1,
        G2 = 2,
        G3 = 3,
        G4 = 4,
        G5 = 5,
        G6 = 6,
        G7 = 7,
        G8 = 8,
        G9 = 9,
        G10 = 10,
        G11 = 11,
        G12 = 12,
        G20 = 20,
        NON_TYPEABLE = 30,
        MIXED = 40,
        OTHER = 50;

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
