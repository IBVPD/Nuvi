<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class Rehydration extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        ORAL     = 1,
        IV       = 2,
        OTHER    = 3,
        BOTH     = 4,
        MULTIPLE = 5,
        UNKNOWN  = 99;

    protected $values = [
        self::ORAL     => 'Oral - ORS/ORT',
        self::IV       => 'IV fluids',
        self::OTHER    => 'Other',
        self::BOTH     => 'Both (ORS/ORT and IV fluids)',
        self::MULTIPLE => 'ORS/ORT and/or IV fluids and/or Other/Multiple',
        self::UNKNOWN  => 'Unknown',
    ];
}
