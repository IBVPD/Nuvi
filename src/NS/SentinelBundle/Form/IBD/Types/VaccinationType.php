<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of VaccinationType
 *
 */
class VaccinationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const MEN_AFR_VAC     = 1;
    const ACYW135_POLY    = 2;
    const ACW135          = 3;
    const ACYW135_CON     = 4;
    const OTHER           = 5;
    const B               = 6;
    const C               = 7;
    const UNKNOWN         = 99;

    protected $values = [
        self::MEN_AFR_VAC  => 'MenAfriVac (conjugate MenA)',
        self::ACYW135_POLY => 'ACYW135 (polysaccharide)',
        self::ACW135       => 'ACW135 (polysaccharide)',
        self::ACYW135_CON  => 'ACYW135 (conjugate)',
        self::B            => 'B recombinante',
        self::C            => 'C (conjugada)',
        self::OTHER        => 'Other',
        self::UNKNOWN      => 'Unknown',
    ];
}
