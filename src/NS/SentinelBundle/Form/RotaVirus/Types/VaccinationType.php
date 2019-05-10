<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of RotavirusVaccinationType
 *
 */
class VaccinationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const GSK = 1;
    public const MERK = 2;
    public const UNKNOWN = 99;

    protected $values = [
        self::GSK => 'Rotarix, GSK',
        self::MERK => 'RotaTeq, Merck',
        self::UNKNOWN => 'Unknown',
    ];
}
