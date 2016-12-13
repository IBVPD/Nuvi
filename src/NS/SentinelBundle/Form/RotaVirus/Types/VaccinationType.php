<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of RotavirusVaccinationType
 *
 */
class VaccinationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const GSK = 1;
    const MERK = 2;
    const UNKNOWN = 99;


    protected $values = [
                                self::GSK     => 'Rotarix, GSK',
                                self::MERK    => 'RotaTeq, Merck',
                                self::UNKNOWN => 'Unknown',
    ];
}
