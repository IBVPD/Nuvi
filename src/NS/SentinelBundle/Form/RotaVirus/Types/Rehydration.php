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
    const ORAL     = 1;
    const IV       = 2;
    const OTHER    = 3;
    const BOTH     = 4;
    const MULTIPLE = 5;
    const UNKNOWN  = 99;

    protected $values = [
        self::ORAL     => 'Oral - ORS/ORT',
        self::IV       => 'IV fluids',
        self::OTHER    => 'Other',
        self::BOTH     => 'Both (ORS/ORT and IV fluids)',
        self::MULTIPLE => 'ORS/ORT and/or IV fluids and/or Other/Multiple',
        self::UNKNOWN  => 'Unknown',
    ];
}
