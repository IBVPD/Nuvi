<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of Rehydration
 *
 */
class Rehydration extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const ORAL     = 1;
    public const IV       = 2;
    public const OTHER    = 3;
    public const BOTH     = 4;
    public const MULTIPLE = 5;
    public const UNKNOWN  = 99;

    protected $values = [
        self::ORAL     => 'Oral - ORS/ORT',
        self::IV       => 'IV fluids',
        self::OTHER    => 'Other',
        self::BOTH     => 'Both (ORS/ORT and IV fluids)',
        self::MULTIPLE => 'ORS/ORT and/or IV fluids and/or Other/Multiple',
        self::UNKNOWN  => 'Unknown',
    ];
}
