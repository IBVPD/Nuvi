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
    public const MEN_AFR_VAC     = 1;
    public const ACYW135_POLY    = 2;
    public const ACW135          = 3;
    public const ACYW135_CON     = 4;
    public const OTHER           = 5;
    public const B               = 6;
    public const C               = 7;
    public const UNKNOWN         = 99;

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
