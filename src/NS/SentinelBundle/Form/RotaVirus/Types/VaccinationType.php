<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class VaccinationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        GSK = 1,
        MERK = 2,
        UNKNOWN = 99;

    protected $values = [
        self::GSK => 'Rotarix, GSK',
        self::MERK => 'RotaTeq, Merck',
        self::UNKNOWN => 'Unknown',
    ];
}
