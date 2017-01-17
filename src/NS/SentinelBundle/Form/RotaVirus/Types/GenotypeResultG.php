<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of GenotypeResultG
 *
 */
class GenotypeResultG extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const G1 = 1;
    const G2 = 2;
    const G3 = 3;
    const G4 = 4;
    const G5 = 5;
    const G6 = 6;
    const G7 = 7;
    const G8 = 8;
    const G9 = 9;
    const G10 = 10;
    const G11 = 11;
    const G12 = 12;
    const G20 = 20;
    const NON_TYPEABLE = 30;
    const MIXED = 40;
    const OTHER = 50;

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
